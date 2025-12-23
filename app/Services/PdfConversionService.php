<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use setasign\Fpdi\Fpdi;

class PdfConversionService
{
    protected string $libreOfficePath = 'libreoffice';
    protected string $uploadPath = 'uploads';
    protected string $pdfPath = 'pdfs';
    protected string $signedPath = 'signed';

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
        ];

        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    public function isLibreOfficeAvailable(): bool
    {
        $output = [];
        $returnCode = 0;
        exec('which libreoffice 2>/dev/null', $output, $returnCode);
        return $returnCode === 0 && !empty($output);
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
            if (!$this->isLibreOfficeAvailable()) {
                Log::warning('LibreOffice not available, storing original file without conversion');
                $result['pdf_path'] = null;
                $result['message'] = 'Word document uploaded but conversion not available';
                return $result;
            }
            
            $convertedPdf = $this->convertToPdf($originalPath, $uniqueName);
            if ($convertedPdf) {
                $result['pdf_path'] = $convertedPdf;
                $result['message'] = 'Word document converted to PDF successfully';
            } else {
                $result['success'] = false;
                $result['message'] = 'Failed to convert Word document to PDF';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Unsupported file type: ' . $extension;
        }

        return $result;
    }

    public function convertToPdf(string $storagePath, string $outputName): ?string
    {
        $fullPath = Storage::path($storagePath);
        $outputDir = Storage::path($this->pdfPath);
        
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        if (!file_exists($fullPath)) {
            Log::error('Source file not found for conversion: ' . $fullPath);
            return null;
        }

        $command = sprintf(
            '%s --headless --convert-to pdf --outdir %s %s 2>&1',
            $this->libreOfficePath,
            escapeshellarg($outputDir),
            escapeshellarg($fullPath)
        );

        Log::info('LibreOffice conversion command: ' . $command);
        
        exec($command, $output, $returnCode);
        
        Log::info('LibreOffice output: ' . implode("\n", $output));
        Log::info('LibreOffice return code: ' . $returnCode);

        $originalBasename = pathinfo($fullPath, PATHINFO_FILENAME);
        $expectedPdfPath = $outputDir . '/' . $originalBasename . '.pdf';
        
        if (file_exists($expectedPdfPath)) {
            $finalPdfName = $outputName . '.pdf';
            $finalPdfPath = $this->pdfPath . '/' . $finalPdfName;
            
            if ($expectedPdfPath !== Storage::path($finalPdfPath)) {
                rename($expectedPdfPath, Storage::path($finalPdfPath));
            }
            
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
            return $finalPdfPath;
        }

        Log::error('PDF conversion failed - no output file found');
        return null;
    }

    public function addSignatureToPdf(string $pdfPath, string $signatureData, array $options = []): ?string
    {
        $this->ensureDirectoriesExist();
        
        $fullPdfPath = Storage::path($pdfPath);
        
        if (!file_exists($fullPdfPath)) {
            Log::error('PDF file not found: ' . $fullPdfPath);
            return null;
        }

        $signatureImage = $this->saveSignatureImage($signatureData);
        if (!$signatureImage) {
            Log::error('Failed to save signature image');
            return null;
        }

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($fullPdfPath);
            
            $signaturePage = $options['page'] ?? $pageCount;
            $signatureX = $options['x'] ?? 120;
            $signatureY = $options['y'] ?? 250;
            $signatureWidth = $options['width'] ?? 50;
            
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
                
                if ($pageNo == $signaturePage) {
                    $pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth);
                }
            }
            
            $signedFileName = 'signed_' . uniqid() . '.pdf';
            $signedPath = $this->signedPath . '/' . $signedFileName;
            $fullSignedPath = Storage::path($signedPath);
            
            if (!file_exists(dirname($fullSignedPath))) {
                mkdir(dirname($fullSignedPath), 0755, true);
            }
            
            $pdf->Output('F', $fullSignedPath);
            
            @unlink($signatureImage);
            
            if (!file_exists($fullSignedPath)) {
                Log::error('Signed PDF was not created');
                return null;
            }
            
            return $signedPath;
            
        } catch (\Exception $e) {
            Log::error('Failed to add signature to PDF: ' . $e->getMessage());
            @unlink($signatureImage);
            return null;
        }
    }

    protected function saveSignatureImage(string $signatureData): ?string
    {
        if (strpos($signatureData, 'data:image/png;base64,') === 0) {
            $signatureData = substr($signatureData, strlen('data:image/png;base64,'));
        }
        
        $imageData = base64_decode($signatureData);
        if ($imageData === false) {
            Log::error('Failed to decode signature base64 data');
            return null;
        }
        
        $tempPath = storage_path('app/temp_signature_' . uniqid() . '.png');
        
        if (file_put_contents($tempPath, $imageData) === false) {
            Log::error('Failed to write signature image to temp file');
            return null;
        }
        
        return $tempPath;
    }

    public function getPdfUrl(string $pdfPath): string
    {
        return route('documents.pdf.view', ['path' => base64_encode($pdfPath)]);
    }
}
