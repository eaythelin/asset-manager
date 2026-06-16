<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\PriorityLevel;
use App\Enums\WorkorderType;

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
            "workorder_type" => ["required", new Enum(WorkorderType::class)],
            "priority_level" => ["nullable", new Enum(PriorityLevel::class)],
            "inhouse_cost" => ["nullable", "numeric", "min:1"],
            "estimated_duration" => ["nullable", "string"],
            "instructions" => ["nullable", "max:255"],

            "sub_name" => ["nullable", "string", "max:100"],
            "sub_document" => ["nullable","string", "max:100"],
            "sub_details" => ["nullable","string", "max:100"],
            "sub_cost" => ["nullable", "numeric", "min:1"],
            "sub_date_released" => ["nullable", "date"],
            "sub_date_returned" => ["nullable", "date", "after_or_equal:sub_date_released"],

            "has_vehicle" => ["boolean"],
            "has_minor" => ["boolean"],
            "vehicle_minor_details" => ["nullable","string", "max:100"],
            "has_major" => ["boolean"],
            "vehicle_major_details" => ["nullable","string", "max:100"],
            "has_change_oil" => ["boolean"],
            "last_change_oil_date" => ["nullable", "date"],
            "meter_reading" => ["nullable", "string", "max:100"],
            "has_insurance" => ["boolean"],
            "insurance_date" => ["nullable", "date"],
            "has_registration" => ["boolean"],
            "registration_date" => ["nullable", "date"],
            "has_other" => ["boolean"],
            "other_details" => ["nullable", "string", "max:255"],

            'has_spare_parts' => ['boolean'],
            'spare_parts' => ['nullable', 'array'],
            'spare_parts.*.part' => ['required', 'string'],
            'spare_parts.*.description' => ['nullable', 'string'],
            'spare_parts.*.quantity' => ['required', 'integer', 'min:1'],

            "accomplishment_details" => ["nullable", "string", "max:255"]
        ];
    }
}
