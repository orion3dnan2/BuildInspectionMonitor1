<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Mpdf\Mpdf;
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
            $convertedPdf = $this->convertWordToPdf($originalPath, $uniqueName);
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

    public function convertWordToPdf(string $storagePath, string $outputName): ?string
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

        try {
            Log::info('Starting Word to PDF conversion using PhpWord + mPDF');
            
            $phpWord = WordIOFactory::load($fullPath);
            
            $tempHtmlPath = storage_path('app/temp_' . uniqid() . '.html');
            $htmlWriter = WordIOFactory::createWriter($phpWord, 'HTML');
            $htmlWriter->save($tempHtmlPath);
            
            $htmlContent = file_get_contents($tempHtmlPath);
            @unlink($tempHtmlPath);
            
            $htmlContent = $this->wrapHtmlForArabic($htmlContent);
            
            $tempDir = storage_path('app/mpdf_temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'dejavusans',
                'tempDir' => $tempDir,
                'autoArabic' => true,
                'autoLangToFont' => true,
            ]);
            
            $mpdf->SetDirectionality('rtl');
            $mpdf->WriteHTML($htmlContent);
            
            $finalPdfName = $outputName . '.pdf';
            $finalPdfPath = $this->pdfPath . '/' . $finalPdfName;
            $fullPdfPath = Storage::path($finalPdfPath);
            
            $mpdf->Output($fullPdfPath, \Mpdf\Output\Destination::FILE);
            
            if (file_exists($fullPdfPath)) {
                Log::info('Word to PDF conversion successful with mPDF: ' . $finalPdfPath);
                return $finalPdfPath;
            }
            
            Log::error('PDF file was not created after conversion');
            return null;
            
        } catch (\Exception $e) {
            Log::error('mPDF conversion failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    protected function wrapHtmlForArabic(string $htmlContent): string
    {
        if (stripos($htmlContent, '<html') === false) {
            $htmlContent = '<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans", "Arial", sans-serif;
            direction: rtl;
            text-align: right;
            line-height: 1.8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
        }
        td, th {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>' . $htmlContent . '</body></html>';
        } else {
            $htmlContent = preg_replace('/<html([^>]*)>/i', '<html$1 dir="rtl" lang="ar">', $htmlContent);
            
            $styleBlock = '<style>
                body {
                    font-family: "DejaVu Sans", "Arial", sans-serif;
                    direction: rtl;
                    text-align: right;
                    line-height: 1.8;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    direction: rtl;
                }
                td, th {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: right;
                }
            </style>';
            
            if (stripos($htmlContent, '</head>') !== false) {
                $htmlContent = str_ireplace('</head>', $styleBlock . '</head>', $htmlContent);
            }
        }
        
        return $htmlContent;
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
