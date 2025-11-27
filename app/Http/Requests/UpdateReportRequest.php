<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'record_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inspection_reports')->ignore($this->route('report')),
            ],
            'outgoing_number' => 'nullable|string|max:255',
            'officer_name' => 'required|string|max:255',
            'rank' => 'nullable|string|max:255',
            'office_name' => 'required|string|max:255',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'record_number.required' => 'رقم السجل مطلوب',
            'record_number.unique' => 'رقم السجل موجود مسبقاً',
            'officer_name.required' => 'اسم الضابط مطلوب',
            'office_name.required' => 'اسم المكتب مطلوب',
            'inspection_date.required' => 'تاريخ التفتيش مطلوب',
            'inspection_date.date' => 'صيغة التاريخ غير صحيحة',
        ];
    }
}
