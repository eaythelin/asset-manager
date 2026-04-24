<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return collect([
            [
                'Asset Name', 'Serial Name (optional)', 'e.g. 50', 'e.g. Furniture', 'e.g. Office Furniture (optional)',
                'optional', 'e.g. Admin (optional)', 'e.g. Juan Dela Cruz (optional)', 'Yes or No',
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
}