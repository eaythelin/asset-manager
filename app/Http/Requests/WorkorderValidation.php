<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\PriorityLevel;
use App\Enums\WorkorderType;
use Illuminate\Validation\Rule;
use App\Models\Workorder;
use Illuminate\Support\Carbon;

class WorkorderValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "has_spare_parts"=> $this->has("has_spare_parts"),
            "has_vehicle"=> $this->has("has_vehicle"),
            "has_minor"=> $this->has("has_minor"),
            "has_major"=> $this->has("has_major"),
            "has_change_oil"=> $this->has("has_change_oil"),
            "has_insurance"=> $this->has("has_insurance"),
            "has_registration"=> $this->has("has_registration"),
            "has_other"=> $this->has("has_other"),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route("id");

        return [
            "type" => ["required", new Enum(WorkorderType::class)],

            "priority_level" => ["nullable", new Enum(PriorityLevel::class)],
            "inhouse_cost" => ["nullable", "numeric", "min:1"],
            "estimated_duration" => ["nullable", "string"],
            "instructions" => ["nullable", "max:255"],
            "employee_1" => ["nullable", "exists:employees,id"],
            "employee_2" => ["nullable", "exists:employees,id"],

            "sub_name" => ["nullable", "string", "max:100"],
            "sub_document" => ["nullable","string", "max:100"],
            "sub_details" => ["nullable","string", "max:100"],
            "sub_cost" => ["nullable", "numeric", "min:1"],
            "sub_date_released" => ["nullable", "date",
                Rule::excludeIf(function () use ($id){
                    if($this->isMethod('PUT') || $this->isMethod('PATCH')){
                        $currentDate = Workorder::where('id', $id)->value('sub_date_released');
                        //if no date in DB, dont exclude validation
                        if (!$currentDate || !$this->input('sub_date_released')) {
                            return false;
                        }

                        // Parse both to Carbon objects so they are compared purely as dates
                        return Carbon::parse($this->input('sub_date_released'))
                            ->isSameDay(Carbon::parse($currentDate));
                    }
                    return false;
                }), 'after_or_equal:today',
                    'before_or_equal:+2months'
            ],
            "sub_date_returned" => ["nullable", "date", "after_or_equal:sub_date_released"],

            "has_vehicle" => ["boolean"],
            "has_minor" => ["boolean"],
            "vehicle_minor_details" => ["nullable","string", "max:100"],
            "has_major" => ["boolean"],
            "vehicle_major_details" => ["nullable","string", "max:100"],
            "has_change_oil" => ["boolean"],
            "last_change_oil_date" => ["nullable", "date", "before_or_equal:today"],
            "meter_reading" => ["nullable", "string", "max:100"],
            "has_insurance" => ["boolean"],
            "insurance_date" => ["nullable", "date", "after_or_equal:today"],
            "has_registration" => ["boolean"],
            "registration_date" => ["nullable", "date", "after_or_equal:today"],
            "has_other" => ["boolean"],
            "other_details" => ["nullable", "string", "max:255"],

            'has_spare_parts' => ['boolean'],
            'spare_parts' => ['nullable', 'array'],
            'spare_parts.*.part' => ['nullable', 'string', 'required_with:spare_parts.*.quantity'],
            'spare_parts.*.description' => ['nullable', 'string'],
            'spare_parts.*.quantity' => ['nullable', 'integer', 'min:1', 'required_with:spare_parts.*.part'],

            "accomplishment_details" => ["nullable", "string", "max:255"]
        ];
    }
}
