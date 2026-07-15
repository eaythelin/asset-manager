<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\Category;
use App\Models\Department;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class AssetTemplateSheetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $categories;
    protected $departments;
    protected $employees;

    public function __construct(){
        $this->categories = Category::pluck('name')->toArray();
        $this->departments = Department::pluck('name')->toArray();
        $this->employees = Employee::pluck('name')->toArray();
    }

    public function collection()
    {
        return collect([
            [
                'Asset Name', 'Serial Name (optional)', 'e.g. 50', 'e.g. Furniture', 'e.g. Office Furniture (optional)',
                'optional', 'e.g. Admin', 'e.g. Juan Dela Cruz (optional)', 'Yes or No',
                'e.g. 10000.00', 'e.g. 1000.00', 'e.g. 2024-01-25', 'e.g. 5 (in years)', 'e.g. Supplier Name (optional)'
            ]
        ]);
    }

    public function headings():array
    {
        return [
            'Asset Name', 'Serial Name', 'Quantity', 'Category', 'Subcategory',
            'Description', 'Department', 'Custodian', 'Is Depreciable (Yes/No)',
            'Cost', 'Salvage Value', 'Acquisition Date', 'Useful Life', 'Supplier'
        ];
    }

    public function styles(Worksheet $sheet)
{
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ],
            2 => [
                'font' => ['italic' => true, 'color' => ['rgb' => '6B7280']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3F4F6']
                ]
            ]
        ];
    }

    public function registerEvents():array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();

                //freeze row1 and row2 and starts at row3
                $sheet->freezePane('A3');
                $sheet->getStyle('A3:M1000')
                    ->getProtection()
                    ->setLocked(Protection::PROTECTION_UNPROTECTED);
                $sheet->getProtection()->setSheet(true);

                //creates the dropdown rule template
                $validation = new DataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowDropDown(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowInputMessage(false);
                $validation->setErrorTitle('Invalid Input');
                $validation->setError('Please select only from the dropdown');

                //load the stuff (max is 255 each though)
                $categoryValidation = clone $validation;
                $categoryValidation->setFormula1('"' . implode(',', $this->categories) . '"');

                $deptValidation = clone $validation;
                $deptValidation->setFormula1('"' . implode(',', $this->departments) . '"');

                $employeeValidation = clone $validation;
                $employeeValidation->setFormula1('"' . implode(',', $this->employees) . '"');

                $depreciableValidation = clone $validation;
                $depreciableValidation->setFormula1('"Yes,No"');

                for($i = 3; $i <= 1000; $i++){
                    $sheet->getCell("D{$i}")->setDataValidation(clone $categoryValidation);
                    $sheet->getCell("G{$i}")->setDataValidation(clone $deptValidation);
                    $sheet->getCell("H{$i}")->setDataValidation(clone $employeeValidation);
                    $sheet->getCell("I{$i}")->setDataValidation(clone $depreciableValidation);

                    //for the dynamic subcategory
                    $subcategoryValidation = clone $validation;
                    $subcategoryValidation->setFormula1('=INDIRECT(SUBSTITUTE(D' . $i . ', " ", "_"))');
                    $sheet->getCell("E{$i}")->setDataValidation($subcategoryValidation);
                }
            }
        ];
    }
}
