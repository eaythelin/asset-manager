<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Department;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AssetImport implements ToModel, WithHeadingRow, WithValidation, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        if (!isset($row['asset_name'])) {
            return null;
        }

        $acquisitionDate = $row['acquisition_date'] ? new \DateTime($row['acquisition_date']) : null;
        $usefulLife = $row['useful_life'];

        $endOfLifeDate = ($acquisitionDate && $usefulLife > 0)
            ? (clone $acquisitionDate)->modify("+{$usefulLife} years")
            : null;
        
        $count = Asset::withTrashed()->count();
        $nextCode = 'AST-'.($count + 1);
        
        return new Asset([
            'asset_code' => $nextCode,
            'name' => $row['asset_name'],
            'serial_name' => $row['serial_name'],
            'quantity' => $row['quantity'],
            'description' => $row['description'],
            'cost' => $row['cost'] ?? 0,
            'salvage_value' => $row['salvage_value'] ?? 0,
            'useful_life_in_years' => $usefulLife,
            'is_depreciable' => $row['is_depreciable'] ?? false,
            'acquisition_date' => $acquisitionDate,
            'end_of_life_date' => $endOfLifeDate, 
            'category_id' => Category::where('name', trim($row['category']))->first()?->id,
            'sub_category_id' => SubCategory::where('name', trim($row['subcategory']))->first()?->id,
            'department_id' => Department::where('name', trim($row['department']))->first()?->id,
            'custodian_id' => Employee::whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$row['custodian']])->first()?->id,
            'supplier_id' => Supplier::where('name', trim($row['supplier']))->first()?->id,
        ]);
    }

    public function rules(): array
    {
        return [
            "asset_name" => ['required', 'string', 'max:100'],
            "serial_name" => ["nullable", "string", "max:100"],
            "category" => ["required", Rule::exists('categories', 'name')],
            "quantity"=> ["required","integer", "min:1"],
            "subcategory" => ["nullable", Rule::exists('sub_categories', 'name')],
            "description" => ["nullable", "string", "max:255"],
            "department" => ["nullable", Rule::exists('departments', 'name')],
            "custodian" => ["nullable",
                function($attribute, $value, $fail){
                    $exists = Employee::whereRaw("CONCAT(first_name, ' ', last_name) = ?", [trim($value)])->exists();
                    if (!$exists) {
                        $fail("Custodian \"{$value}\" not found in employees.");
                    }
                }],
            "is_depreciable" => ["nullable", "boolean"],
            "cost" => ["required_if:is_depreciable,true", "nullable", "numeric"],
            "salvage_value" => ["required_if:is_depreciable,true", "nullable", "numeric"],
            "acquisition_date" => ["required_if:is_depreciable,true", "nullable", "date"],
            "useful_life" => ["required_if:is_depreciable,true", "nullable", "integer"],
            "supplier" => ["nullable", Rule::exists('suppliers', 'name')]
        ];
    }

    public function prepareForValidation($data){
        if (isset($data['asset_name'])) {
            return $data;
        }
        $data['category'] = isset($data['category']) ? trim($data['category']) : null;
        $data['subcategory'] = isset($data['subcategory']) ? trim($data['subcategory']) : null;
        $data['department'] = isset($data['department']) ? trim($data['department']) : null; 
        $data['supplier'] = isset($data['supplier']) ? trim($data['supplier']) : null;
        $data['custodian'] = isset($data['custodian']) ? trim($data['custodian']) : null;
        $data['is_depreciable'] = str_starts_with(strtolower($data['is_depreciable_yesno'] ?? 'n'), 'y');
        $data['acquisition_date'] = !empty($data['acquisition_date'])
            ? Date::excelToDateTimeObject($data['acquisition_date'])->format('Y-m-d')
            : null;
        $data['useful_life'] = is_numeric($data['useful_life'] ?? null) ? (int) $data['useful_life'] : 0;
        return $data;
    }
}