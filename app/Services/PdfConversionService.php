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
                $result['pdf_path'] = null;
                $result['message'] = 'تم رفع ملف Word بنجاح (سيتم عرضه مباشرة)';
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
        
        $extension = pathinfo($fullInputPath, PATHINFO_EXTENSION);
        $tempName = 'temp_' . uniqid() . '.' . $extension;
        $tempInputPath = sys_get_temp_dir() . '/' . $tempName;
        $tempOutputDir = sys_get_temp_dir();
        
        if (!copy($fullInputPath, $tempInputPath)) {
            Log::error('Failed to copy file to temp location');
            return null;
        }
        
        $javaHome = $this->findJavaHome();
        $fontconfigFile = storage_path('app/fontconfig/fonts.conf');
        $fontconfigCache = storage_path('app/fontconfig/cache');
        
        $env = [
            'HOME=' . storage_path('app'),
            'SAL_USE_VCLPLUGIN=svp',
            'FONTCONFIG_FILE=' . $fontconfigFile,
            'FONTCONFIG_PATH=' . dirname($fontconfigFile),
            'XDG_CACHE_HOME=' . storage_path('app/fontconfig'),
        ];
        
        if ($javaHome) {
            $env[] = 'JAVA_HOME=' . $javaHome;
            $env[] = 'PATH=' . $javaHome . '/bin:' . getenv('PATH');
        }
        
        $command = sprintf(
            'env %s %s --headless --nofirststartwizard --norestore ' .
            '-env:UserInstallation=file://%s ' .
            '--convert-to pdf ' .
            '--outdir %s ' .
            '%s 2>&1',
            implode(' ', $env),
            escapeshellarg($libreOfficeBin),
            $profileDir,
            escapeshellarg($tempOutputDir),
            escapeshellarg($tempInputPath)
        );

        Log::info('LibreOffice command: ' . $command);
        
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        Log::info('LibreOffice output: ' . implode("\n", $output));
        Log::info('LibreOffice return code: ' . $returnCode);

        @unlink($tempInputPath);

        $tempBasename = pathinfo($tempInputPath, PATHINFO_FILENAME);
        $tempPdfPath = $tempOutputDir . '/' . $tempBasename . '.pdf';
        
        if (file_exists($tempPdfPath)) {
            $finalPdfPath = $this->pdfPath . '/' . $outputName . '.pdf';
            $finalFullPath = $this->getFullPath($finalPdfPath);
            
            if (!rename($tempPdfPath, $finalFullPath)) {
                copy($tempPdfPath, $finalFullPath);
                @unlink($tempPdfPath);
            }
            
            Log::info('LibreOffice conversion successful: ' . $finalPdfPath);
            return $finalPdfPath;
        }

        $pdfFiles = glob($tempOutputDir . '/temp_*.pdf');
        if (!empty($pdfFiles)) {
            usort($pdfFiles, fn($a, $b) => filemtime($b) - filemtime($a));
            $latestPdf = $pdfFiles[0];
            $finalPdfPath = $this->pdfPath . '/' . $outputName . '.pdf';
            $finalFullPath = $this->getFullPath($finalPdfPath);
            
            if (!rename($latestPdf, $finalFullPath)) {
                copy($latestPdf, $finalFullPath);
                @unlink($latestPdf);
            }
            
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

    protected function findJavaHome(): ?string
    {
        $output = [];
        $returnCode = 0;
        exec('which java 2>/dev/null', $output, $returnCode);
        
        if ($returnCode === 0 && !empty($output)) {
            $javaPath = trim($output[0]);
            return dirname(dirname($javaPath));
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
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->SetMargins(0, 0, 0);
            
            $pageCount = $pdf->setSourceFile($fullPdfPath);
            
            $signaturePage = isset($options['page']) && $options['page'] > 0 ? (int)$options['page'] : $pageCount;
            if ($signaturePage > $pageCount) {
                $signaturePage = $pageCount;
            }
            
            $signatureX = isset($options['x']) ? (float)$options['x'] : 120;
            $signatureY = isset($options['y']) ? (float)$options['y'] : 200;
            $signatureWidth = isset($options['width']) ? (float)$options['width'] : 50;
            
            Log::info("Adding signature to page {$signaturePage} of {$pageCount} at position ({$signatureX}, {$signatureY})");
            
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                $orientation = $size['orientation'];
                $pageWidth = $size['width'];
                $pageHeight = $size['height'];
                
                $pdf->AddPage($orientation, [$pageWidth, $pageHeight]);
                
                $pdf->useTemplate($templateId, 0, 0, $pageWidth, $pageHeight, true);
                
                if ($pageNo === $signaturePage) {
                    $adjustedX = max(0, min($signatureX, $pageWidth - $signatureWidth));
                    $adjustedY = max(0, min($signatureY, $pageHeight - 20));
                    
                    Log::info("Placing signature image at ({$adjustedX}, {$adjustedY}) with width {$signatureWidth}");
                    
                    $pdf->Image($fullSignaturePath, $adjustedX, $adjustedY, $signatureWidth, 0, '', '', '', false, 300);
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
            
            $originalPageCount = $pageCount;
            $verifyPdf = new Fpdi();
            $signedPageCount = $verifyPdf->setSourceFile($fullSignedPath);
            
            if ($signedPageCount !== $originalPageCount) {
                Log::error("Page count mismatch: original={$originalPageCount}, signed={$signedPageCount}");
            }
            
            Log::info("Signature added successfully: {$signedPath} (pages: {$signedPageCount})");
            return $signedPath;
            
        } catch (\Exception $e) {
            Log::error('Signature error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return null;
        }
    }
}
