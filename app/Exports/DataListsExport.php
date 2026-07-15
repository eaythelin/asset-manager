<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\NamedRange;
use App\Models\Category;

class DataListsExport implements FromCollection, WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function title(): string
    {
        return 'DataLists';
    }
    public function collection()
    {
        $categories = Category::with('subCategories')->get();
        $matrix = [];

        //build matrix where the first row is category names and rows below are their subcategories
        $headings = [];
        $maxSubcategoriesCount = 0;

        foreach($categories as $category){
            //just remove empty spaces
            $safeName = str_replace(' ', '_', $category->name);
            $headings[] = $safeName;

            $maxSubcategoriesCount = max($maxSubcategoriesCount, $category->subCategories->count());
        }

        $matrix[] = $headings;
        for ($i = 0; $i < $maxSubcategoriesCount; $i++){
            $row = [];
            foreach($categories as $category){
                $subcategoriesArray = $category->subCategories->pluck('name')->toArray();
                $row[] = $subcategoriesArray[$i] ?? ''; // fill with blank if category has fewer subcategories
            }
            $matrix[] = $row;
        }
        return collect($matrix);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Fetch categories again to match coordinates
                $categories = Category::with('subcategories')->get();
                $colIndex = 'A';

                foreach ($categories as $category) {
                    $subCount = $category->subcategories->count();

                    if ($subCount > 0) {
                        // Clean category name (e.g., "Office_Furniture")
                        $safeName = str_replace(' ', '_', $category->name);

                        // Define the range of the subcategories list (e.g., A2:A15)
                        $startCell = "{$colIndex}2";
                        $endCell = "{$colIndex}" . ($subCount + 1);

                        // Create the Named Range in Excel
                        $event->sheet->getParent()->addNamedRange(
                            new NamedRange(
                                $safeName,
                                $event->sheet->getDelegate(),
                                "\${$colIndex}\$2:\${$colIndex}\$" . ($subCount + 1)
                            )
                        );
                    }
                    $colIndex++;
                }

                //set the sheet state to HIDDEN so users don't see it
                $event->sheet->getParent()->getSheetByName('DataLists')
                    ->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
            }
        ];
    }
}
