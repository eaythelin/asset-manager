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

        return [
          "control_number" => $id
            ? ["required", Rule::unique("requests", "control_number")->ignore($id)]
            : ["required", "unique:requests"],
          "asset" => ["required", "exists:assets,id"],
          "requisitioner" => ["required", "exists:users,id"],
          "department" => ["required", "exists:departments,id"],
          "description" => ["required", "string", "max:255"],
          "request_type" => ["required", new Enum(RequestTypes::class)]
        ];
    }
}
