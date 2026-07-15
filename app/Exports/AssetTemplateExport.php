<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AssetTemplateExport implements WithMultipleSheets
{
    public function sheets():array
    {
        return [
            new AssetTemplateSheetExport(),
            new DataListsExport()
        ];
    }
}
