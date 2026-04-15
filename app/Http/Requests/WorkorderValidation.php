<?php

namespace App\Http\Requests;

use App\Enums\DisposalMethods;
use App\Enums\MaintenanceType;
use App\Enums\PriorityLevel;
use App\Enums\ServiceTypes;
use App\Enums\WorkorderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class WorkorderValidation extends FormRequest
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
            //general fields
            "priority_level" => ["nullable", new Enum(PriorityLevel::class)],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date", "after_or_equal:start_date"],
        ];

        return match($this->workorder_type){
            WorkorderType::DISPOSAL->value => array_merge($baseRules, [
                "disposal_method" => ["required", new Enum(DisposalMethods::class)],
                "disposal_date" => ["required_if:status,in_progress", "nullable", "date", "after_or_equal:start_date"],
                "reason" => ["nullable", "string", "max:500"],
                "quantity" => ["required", "integer", "min:1"]
            ]),
            WorkorderType::SERVICE->value => array_merge($baseRules, [
                "maintenance_type" => ["required", new Enum(MaintenanceType::class)],
                "instructions" => ["nullable", "string", "max:500"],
                "accomplishment_report" => ["nullable", "string", "max:500"],
                "cost" => ["required", "numeric", "min:0"],
                ...match($this->maintenance_type){
                    MaintenanceType::IN_HOUSE->value => [
                        "assigned_to" => ["required","exists:employees,id"],
                        "estimated_hours" => ["required","numeric", "min:0"],
                    ],
                    MaintenanceType::SUBCONTRACTOR->value => [
                        "subcontractor_name" => ["required","string", "max:255"],
                        "subcontractor_details" => ["nullable", "string", "max:500"]
                    ],
                    default => []
                }
            ]),
            WorkorderType::REQUISITION->value => array_merge($baseRules, [
                "is_new_asset"=> ["required", "boolean"],
                "supplier_id" => ["nullable", "exists:suppliers,id"],
                "description" => ["nullable", "string", "max:500"],
                ...match($this->is_new_asset){
                    "1" => [
                        "asset_name" => ["required","max:100", "string"],
                        "acquisition_date" => [Rule::requiredIf(in_array($this->status, ['in_progress', 'overdue'])),"nullable", "date", "after_or_equal:start_date"],
                        "estimated_cost" => [Rule::requiredIf(in_array($this->status, ['in_progress', 'overdue'])), "nullable", "numeric", "min:0"],
                    ],
                    "0" => [    
                        // "asset_id" => ["required", "exists:assets,id"], //nothing o3o
                    ],
                    default => []
                }
            ]),
            default => $baseRules
        };
    }
}
