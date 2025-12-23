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
            Storage::path($this->uploadPath),
            Storage::path($this->pdfPath),
            Storage::path($this->signedPath),
            Storage::path($this->signaturesPath),
            storage_path('app/libreoffice_profile'),
        ];

        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }
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
            $result['message'] = 'PDF file uploaded successfully';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $convertedPdf = $this->convertWordToPdfWithLibreOffice($originalPath, $uniqueName);
            if ($convertedPdf) {
                $result['pdf_path'] = $convertedPdf;
                $result['message'] = 'Word document converted to PDF successfully';
            } else {
                $result['success'] = false;
                $result['message'] = 'Failed to convert Word document to PDF. Please upload a PDF file directly.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Unsupported file type: ' . $extension;
        }

        return $result;
    }

    public function convertWordToPdfWithLibreOffice(string $storagePath, string $outputName): ?string
    {
        $fullPath = Storage::path($storagePath);
        $outputDir = Storage::path($this->pdfPath);
        $profileDir = storage_path('app/libreoffice_profile');
        
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        if (!file_exists($fullPath)) {
            Log::error('Source file not found for conversion: ' . $fullPath);
            return null;
        }

        $libreOfficePath = $this->findLibreOffice();
        if (!$libreOfficePath) {
            Log::error('LibreOffice not found in system');
            return null;
        }

        try {
            Log::info('Starting Word to PDF conversion using LibreOffice');
            
            putenv('HOME=' . storage_path('app'));
            putenv('SAL_USE_VCLPLUGIN=svp');
            
            $command = sprintf(
                '%s --headless --nofirststartwizard --norestore ' .
                '-env:UserInstallation=file://%s ' .
                '--convert-to pdf:writer_pdf_Export ' .
                '--outdir %s %s 2>&1',
                escapeshellcmd($libreOfficePath),
                escapeshellarg($profileDir),
                escapeshellarg($outputDir),
                escapeshellarg($fullPath)
            );

            Log::info('LibreOffice command: ' . $command);
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            Log::info('LibreOffice output: ' . implode("\n", $output));
            Log::info('LibreOffice return code: ' . $returnCode);

            $originalBasename = pathinfo($fullPath, PATHINFO_FILENAME);
            $expectedPdfPath = $outputDir . '/' . $originalBasename . '.pdf';
            
            if (file_exists($expectedPdfPath)) {
                $finalPdfName = $outputName . '.pdf';
                $finalPdfPath = $this->pdfPath . '/' . $finalPdfName;
                $fullFinalPath = Storage::path($finalPdfPath);
                
                if ($expectedPdfPath !== $fullFinalPath) {
                    rename($expectedPdfPath, $fullFinalPath);
                }
                
                Log::info('Word to PDF conversion successful: ' . $finalPdfPath);
                return $finalPdfPath;
            }

            $pdfFiles = glob($outputDir . '/*.pdf');
            if (!empty($pdfFiles)) {
                usort($pdfFiles, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                $latestPdf = $pdfFiles[0];
                $finalPdfName = $outputName . '.pdf';
                $finalPdfPath = $this->pdfPath . '/' . $finalPdfName;
                rename($latestPdf, Storage::path($finalPdfPath));
                Log::info('Word to PDF conversion successful (fallback): ' . $finalPdfPath);
                return $finalPdfPath;
            }

            Log::error('PDF file was not created after LibreOffice conversion');
            return null;
            
        } catch (\Exception $e) {
            Log::error('LibreOffice conversion failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function findLibreOffice(): ?string
    {
        $paths = [
            '/nix/store/s77ki6j3if918jk373md4aajqii531rd-libreoffice-24.8.7.2-wrapped/bin/libreoffice',
            'libreoffice',
            'soffice',
            '/usr/bin/libreoffice',
            '/usr/bin/soffice',
        ];

        foreach ($paths as $path) {
            $output = [];
            $returnCode = 0;
            exec('which ' . escapeshellarg($path) . ' 2>/dev/null', $output, $returnCode);
            if ($returnCode === 0 && !empty($output)) {
                return trim($output[0]);
            }
            
            if (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }

        exec('which libreoffice 2>/dev/null', $output, $returnCode);
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
            Log::error('Invalid signature image format: ' . $extension);
            return null;
        }
        
        $fileName = 'signature_' . uniqid() . '.' . $extension;
        $path = $file->storeAs($this->signaturesPath, $fileName);
        
        return $path;
    }

    public function addSignatureToPdf(string $pdfPath, string $signatureImagePath, array $options = []): ?string
    {
        $this->ensureDirectoriesExist();
        
        $fullPdfPath = Storage::path($pdfPath);
        $fullSignatureImagePath = Storage::path($signatureImagePath);
        
        if (!file_exists($fullPdfPath)) {
            Log::error('PDF file not found: ' . $fullPdfPath);
            return null;
        }

        if (!file_exists($fullSignatureImagePath)) {
            Log::error('Signature image not found: ' . $fullSignatureImagePath);
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
                    $pdf->Image($fullSignatureImagePath, $signatureX, $signatureY, $signatureWidth);
                }
            }
            
            $signedFileName = 'signed_' . uniqid() . '.pdf';
            $signedPath = $this->signedPath . '/' . $signedFileName;
            $fullSignedPath = Storage::path($signedPath);
            
            if (!file_exists(dirname($fullSignedPath))) {
                mkdir(dirname($fullSignedPath), 0755, true);
            }
            
            $pdf->Output($fullSignedPath, 'F');
            
            if (!file_exists($fullSignedPath)) {
                Log::error('Signed PDF was not created');
                return null;
            }
            
            return $signedPath;
            
        } catch (\Exception $e) {
            Log::error('Failed to add signature to PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    public function getPdfUrl(string $pdfPath): string
    {
        return route('documents.pdf.view', ['path' => base64_encode($pdfPath)]);
    }
}
