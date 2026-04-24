<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetValidation extends FormRequest
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

        return [
            //general fields
            "asset_code" => $id    
                ? ["required",Rule::unique('assets', 'asset_code')->ignore($id)]
                : ["required", "unique:assets"],
            "asset_name" => ["required", "string", "max:100"],
            "serial_name" => ["nullable", "string", "max:100"],
            "category" => ["required", "exists:categories,id"],
            "quantity"=> ["required","integer", "min:1"],
            "subcategory" => ["nullable", "exists:sub_categories,id"],
            "description" => ["nullable", "string", "max:255"],
            "image_path" => ["nullable", "image", "mimes:jpeg,png,jpg,webp", "max:5120"], //max 5MB

            //assignment fields
            "department" => ["required", "exists:departments,id"],
            "custodian" => ["nullable", "exists:employees,id"],

            //financial fields!!
            "is_depreciable" => ["nullable"],
            "cost" => ["required_if:is_depreciable,on","nullable","numeric", "min:0"],
            "salvage_value" => ["required_if:is_depreciable,on", "nullable", "numeric", "min:0"],
            "acquisition_date" => ["required_if:is_depreciable,on", "nullable", "date"],
            "useful_life_in_years" => ["required_if:is_depreciable,on", "nullable", "integer", "min:1"],
            "end_of_life_date" => ["required_if:is_depreciable,on", "nullable", "date"],

            //misc fields
            "supplier" => ["nullable", "exists:suppliers,id"]
        ];
    }
}
