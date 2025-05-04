<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EBRTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new EBRCustomerExport(),
            new EBROperationExport(),
        ];
    }
}
