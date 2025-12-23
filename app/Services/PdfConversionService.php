<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Settings as PhpWordSettings;
use Dompdf\Dompdf;
use Dompdf\Options;
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
            Log::info('Starting Word to PDF conversion using PhpWord + Dompdf');
            
            PhpWordSettings::setPdfRendererName(PhpWordSettings::PDF_RENDERER_DOMPDF);
            PhpWordSettings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
            
            $phpWord = WordIOFactory::load($fullPath);
            
            $finalPdfName = $outputName . '.pdf';
            $finalPdfPath = $this->pdfPath . '/' . $finalPdfName;
            $fullPdfPath = Storage::path($finalPdfPath);
            
            $pdfWriter = WordIOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($fullPdfPath);
            
            if (file_exists($fullPdfPath)) {
                Log::info('Word to PDF conversion successful: ' . $finalPdfPath);
                return $finalPdfPath;
            }
            
            Log::error('PDF file was not created after conversion');
            return null;
            
        } catch (\Exception $e) {
            Log::error('PhpWord conversion failed: ' . $e->getMessage());
            return $this->convertWordToPdfFallback($fullPath, $outputName);
        }
    }

    protected function convertWordToPdfFallback(string $fullPath, string $outputName): ?string
    {
        try {
            Log::info('Trying fallback conversion: Word -> HTML -> PDF');
            
            $phpWord = WordIOFactory::load($fullPath);
            
            $tempHtmlPath = storage_path('app/temp_' . uniqid() . '.html');
            $htmlWriter = WordIOFactory::createWriter($phpWord, 'HTML');
            $htmlWriter->save($tempHtmlPath);
            
            $htmlContent = file_get_contents($tempHtmlPath);
            @unlink($tempHtmlPath);
            
            $htmlContent = '<html dir="rtl"><head><meta charset="UTF-8"><style>
                body { font-family: DejaVu Sans, Arial, sans-serif; direction: rtl; text-align: right; }
            </style></head><body>' . $htmlContent . '</body></html>';
            
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($htmlContent, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $finalPdfName = $outputName . '.pdf';
            $finalPdfPath = $this->pdfPath . '/' . $finalPdfName;
            $fullPdfPath = Storage::path($finalPdfPath);
            
            file_put_contents($fullPdfPath, $dompdf->output());
            
            if (file_exists($fullPdfPath)) {
                Log::info('Fallback Word to PDF conversion successful: ' . $finalPdfPath);
                return $finalPdfPath;
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Fallback conversion also failed: ' . $e->getMessage());
            return null;
        }
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
