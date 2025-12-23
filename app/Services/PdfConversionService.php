<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfConversionService
{
    protected string $uploadPath = 'uploads';
    protected string $pdfPath = 'pdfs';
    protected string $signedPath = 'signed';
    protected string $signaturesPath = 'signatures';

    public function __construct()
    {
        $this->ensureDirectoriesExist();
    }

    protected function ensureDirectoriesExist(): void
    {
        $directories = [
            $this->getFullPath($this->uploadPath),
            $this->getFullPath($this->pdfPath),
            $this->getFullPath($this->signedPath),
            $this->getFullPath($this->signaturesPath),
            storage_path('app/libreoffice_profile'),
        ];

        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    protected function getFullPath(string $relativePath): string
    {
        return Storage::path($relativePath);
    }

    public function uploadAndConvert(UploadedFile $file, ?string $customName = null): array
    {
        $this->ensureDirectoriesExist();
        
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = $customName ?? pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^a-zA-Z0-9_\-\x{0600}-\x{06FF}]/u', '_', $baseName);
        $uniqueName = $baseName . '_' . uniqid();
        
        $originalPath = $file->storeAs($this->uploadPath, $uniqueName . '.' . $extension);
        
        $result = [
            'original_file_path' => $originalPath,
            'original_name' => $originalName,
            'pdf_path' => null,
            'success' => true,
            'message' => '',
        ];

        if ($extension === 'pdf') {
            $result['pdf_path'] = $originalPath;
            $result['message'] = 'تم رفع ملف PDF بنجاح';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $convertedPdf = $this->convertWithLibreOffice($originalPath, $uniqueName);
            if ($convertedPdf) {
                $result['pdf_path'] = $convertedPdf;
                $result['message'] = 'تم تحويل ملف Word إلى PDF بنجاح';
            } else {
                $result['success'] = false;
                $result['message'] = 'فشل في تحويل ملف Word إلى PDF. يرجى رفع ملف PDF مباشرة.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'نوع الملف غير مدعوم: ' . $extension;
        }

        return $result;
    }

    protected function convertWithLibreOffice(string $storagePath, string $outputName): ?string
    {
        $fullInputPath = $this->getFullPath($storagePath);
        $outputDir = $this->getFullPath($this->pdfPath);
        $profileDir = storage_path('app/libreoffice_profile');
        
        if (!file_exists($fullInputPath)) {
            Log::error('Source file not found: ' . $fullInputPath);
            return null;
        }

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $libreOfficeBin = $this->findLibreOffice();
        if (!$libreOfficeBin) {
            Log::error('LibreOffice not found');
            return null;
        }

        Log::info('Converting with LibreOffice: ' . $fullInputPath);
        
        $env = [
            'HOME=' . storage_path('app'),
            'SAL_USE_VCLPLUGIN=svp',
        ];
        
        $command = sprintf(
            'env %s %s --headless --nofirststartwizard --norestore ' .
            '-env:UserInstallation=file://%s ' .
            '--convert-to pdf ' .
            '--outdir %s ' .
            '%s 2>&1',
            implode(' ', $env),
            escapeshellarg($libreOfficeBin),
            $profileDir,
            escapeshellarg($outputDir),
            escapeshellarg($fullInputPath)
        );

        Log::info('LibreOffice command: ' . $command);
        
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        Log::info('LibreOffice output: ' . implode("\n", $output));
        Log::info('LibreOffice return code: ' . $returnCode);

        $inputBasename = pathinfo($fullInputPath, PATHINFO_FILENAME);
        $expectedPdf = $outputDir . '/' . $inputBasename . '.pdf';
        
        if (file_exists($expectedPdf)) {
            $finalPdfPath = $this->pdfPath . '/' . $outputName . '.pdf';
            $finalFullPath = $this->getFullPath($finalPdfPath);
            
            if ($expectedPdf !== $finalFullPath) {
                rename($expectedPdf, $finalFullPath);
            }
            
            Log::info('LibreOffice conversion successful: ' . $finalPdfPath);
            return $finalPdfPath;
        }

        $pdfFiles = glob($outputDir . '/*.pdf');
        if (!empty($pdfFiles)) {
            usort($pdfFiles, fn($a, $b) => filemtime($b) - filemtime($a));
            $latestPdf = $pdfFiles[0];
            $finalPdfPath = $this->pdfPath . '/' . $outputName . '.pdf';
            rename($latestPdf, $this->getFullPath($finalPdfPath));
            Log::info('LibreOffice conversion successful (fallback): ' . $finalPdfPath);
            return $finalPdfPath;
        }

        Log::error('LibreOffice conversion failed - no PDF output');
        return null;
    }

    protected function findLibreOffice(): ?string
    {
        $output = [];
        $returnCode = 0;
        exec('which libreoffice 2>/dev/null', $output, $returnCode);
        
        if ($returnCode === 0 && !empty($output)) {
            return trim($output[0]);
        }

        exec('which soffice 2>/dev/null', $output, $returnCode);
        if ($returnCode === 0 && !empty($output)) {
            return trim($output[0]);
        }

        return null;
    }

    public function saveSignatureImage(UploadedFile $file): ?string
    {
        $this->ensureDirectoriesExist();
        
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
            Log::error('Invalid signature format: ' . $extension);
            return null;
        }
        
        $fileName = 'signature_' . uniqid() . '.' . $extension;
        return $file->storeAs($this->signaturesPath, $fileName);
    }

    public function addSignatureToPdf(string $pdfPath, string $signatureImagePath, array $options = []): ?string
    {
        $this->ensureDirectoriesExist();
        
        $fullPdfPath = $this->getFullPath($pdfPath);
        $fullSignaturePath = $this->getFullPath($signatureImagePath);
        
        if (!file_exists($fullPdfPath)) {
            Log::error('PDF not found: ' . $fullPdfPath);
            return null;
        }

        if (!file_exists($fullSignaturePath)) {
            Log::error('Signature not found: ' . $fullSignaturePath);
            return null;
        }

        try {
            $pdf = new Fpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            $pageCount = $pdf->setSourceFile($fullPdfPath);
            
            $signaturePage = $options['page'] ?? $pageCount;
            $signatureX = $options['x'] ?? 120;
            $signatureY = $options['y'] ?? 240;
            $signatureWidth = $options['width'] ?? 50;
            
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
                
                if ($pageNo == $signaturePage) {
                    $pdf->Image($fullSignaturePath, $signatureX, $signatureY, $signatureWidth);
                }
            }
            
            $signedFileName = 'signed_' . uniqid() . '.pdf';
            $signedPath = $this->signedPath . '/' . $signedFileName;
            $fullSignedPath = $this->getFullPath($signedPath);
            
            $pdf->Output($fullSignedPath, 'F');
            
            if (!file_exists($fullSignedPath)) {
                Log::error('Signed PDF not created');
                return null;
            }
            
            Log::info('Signature added successfully: ' . $signedPath);
            return $signedPath;
            
        } catch (\Exception $e) {
            Log::error('Signature error: ' . $e->getMessage());
            return null;
        }
    }
}
