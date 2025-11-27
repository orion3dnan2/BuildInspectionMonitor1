<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Log;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file');
        
        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return back()->with('error', 'الملف لا يحتوي على بيانات');
            }

            $header = array_map('trim', $rows[0]);
            $requiredColumns = [
                'رقم الصادر', 'الرقم العسكري', 'الاسم الأول', 'تاريخ الجولة'
            ];

            foreach ($requiredColumns as $col) {
                if (!in_array($col, $header)) {
                    return back()->with('error', "العمود المطلوب '{$col}' غير موجود في الملف");
                }
            }

            $columnMap = [
                'رقم الصادر' => 'record_number',
                'الرقم العسكري' => 'military_id',
                'الاسم الأول' => 'first_name',
                'الاسم الثاني' => 'second_name',
                'الاسم الثالث' => 'third_name',
                'الاسم الرابع' => 'fourth_name',
                'الرتبة' => 'rank',
                'المحافظة' => 'governorate',
                'المخفر' => 'station',
                'نوع الإجراء' => 'action_type',
                'المنافذ' => 'ports',
                'الملاحظات' => 'notes',
                'تاريخ الجولة' => 'round_date',
            ];

            $headerIndexes = [];
            foreach ($header as $index => $columnName) {
                if (isset($columnMap[$columnName])) {
                    $headerIndexes[$columnMap[$columnName]] = $index;
                }
            }

            $imported = 0;
            $errors = [];

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                try {
                    $recordNumber = $row[$headerIndexes['record_number']] ?? null;
                    
                    if (empty($recordNumber)) {
                        $errors[] = "الصف {$i}: رقم الصادر فارغ";
                        continue;
                    }

                    if (Record::where('record_number', $recordNumber)->exists()) {
                        $errors[] = "الصف {$i}: رقم الصادر '{$recordNumber}' موجود مسبقاً";
                        continue;
                    }

                    $roundDate = null;
                    if (isset($headerIndexes['round_date']) && !empty($row[$headerIndexes['round_date']])) {
                        $dateValue = $row[$headerIndexes['round_date']];
                        if (is_numeric($dateValue)) {
                            $roundDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue)->format('Y-m-d');
                        } else {
                            $roundDate = date('Y-m-d', strtotime($dateValue));
                        }
                    }

                    Record::create([
                        'record_number' => $recordNumber,
                        'military_id' => $row[$headerIndexes['military_id']] ?? '',
                        'first_name' => $row[$headerIndexes['first_name']] ?? '',
                        'second_name' => $row[$headerIndexes['second_name']] ?? null,
                        'third_name' => $row[$headerIndexes['third_name']] ?? null,
                        'fourth_name' => $row[$headerIndexes['fourth_name']] ?? null,
                        'rank' => $row[$headerIndexes['rank']] ?? null,
                        'governorate' => $row[$headerIndexes['governorate']] ?? null,
                        'station' => $row[$headerIndexes['station']] ?? null,
                        'action_type' => $row[$headerIndexes['action_type']] ?? null,
                        'ports' => $row[$headerIndexes['ports']] ?? null,
                        'notes' => $row[$headerIndexes['notes']] ?? null,
                        'round_date' => $roundDate ?? now()->format('Y-m-d'),
                        'created_by' => auth()->id(),
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "الصف {$i}: " . $e->getMessage();
                }
            }

            Log::record('import', "استيراد {$imported} سجل من ملف Excel");

            $message = "تم استيراد {$imported} سجل بنجاح";
            if (count($errors) > 0) {
                $message .= ". " . count($errors) . " أخطاء";
            }

            return back()
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء قراءة الملف: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = [
            'رقم الصادر', 'الرقم العسكري', 'الاسم الأول', 'الاسم الثاني', 
            'الاسم الثالث', 'الاسم الرابع', 'الرتبة', 'المحافظة', 
            'المخفر', 'نوع الإجراء', 'المنافذ', 'الملاحظات', 'تاريخ الجولة'
        ];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '1', $header);
        }

        $sheet->setRightToLeft(true);
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'import_template.xlsx';
        $path = storage_path('app/public/' . $filename);
        
        $writer->save($path);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
