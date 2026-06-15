<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\AssetStatus;
use App\Enums\DisposalMethods;
use App\Enums\ReportType;
class ReportValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route("id");

        $baseRules = [
            'report_type' => ['required', new Enum(ReportType::class)],
            'department_id' => ['nullable', 'exists:departments,id'],
        ];

        return match($this->report_type){
            ReportType::ASSET->value => array_merge($baseRules, [
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
                'custodian_id' => ['nullable', 'exists:employees,id'],
                'category_id' => ['nullable', 'exists:categories,id'],
                'status' => ['nullable', new Enum(AssetStatus::class)],
            ]),
            ReportType::DEPRECIATION->value => array_merge($baseRules,[
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
                'category_id' => ['nullable', 'exists:categories,id'],
                'status' => ['nullable', new Enum(AssetStatus::class)],
            ]),
            ReportType::DISPOSAL->value => array_merge($baseRules,[
                'asset' => ['nullable', 'exists:assets,id'],
                'disposal_date_from' => ['nullable', 'date'],
                'disposal_date_to' => ['nullable', 'date', 'after_or_equal:disposal_date_from'],
                'disposal_method' => ['nullable', new Enum(DisposalMethods::class)]
            ]),
            default => $baseRules
        };
    }
}
