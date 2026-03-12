<?php

namespace App\Http\Requests;

use App\Enums\DisposalConditions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
class RequestValidation extends FormRequest
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
            "request_code"=> $id
                ? ["required", Rule::unique('requests','request_code')->ignore($id)]
                : ["required", "unique:requests"],
            "type" => ["required", new Enum(RequestTypes::class)],
            "description" => ["nullable", "string", "max:500"],
            //filez
            "attachments" => ["nullable", "array", "max:5"],
            "attachments.*" => ["file", "max:10240", "mimes:jpg,jpeg,png,pdf,doc,docx"]
        ];

        return match($this->type){
            RequestTypes::REQUISITION->value => array_merge($baseRules, [
                "asset_name" => ["required", "max:100", "string"],
                "category" => ["required", "exists:categories,id"],
                "subcategory" => ["nullable", "exists:sub_categories,id"],
            ]),
            RequestTypes::SERVICE->value => array_merge($baseRules,[
                "asset_id" => ["required", "exists:assets,id"],
                "service_type" => ["required", new Enum(ServiceTypes::class)]
            ]),
            RequestTypes::DISPOSAL->value => array_merge($baseRules, [
                "asset_id" => ["required", "exists:assets,id"],
                "condition" => ["required", new Enum(DisposalConditions::class)]
            ]),
            default => $baseRules
        };
    }
}
