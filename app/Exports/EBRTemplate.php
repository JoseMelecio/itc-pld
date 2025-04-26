<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EBRTemplate implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new EBRCustomers(),
            new EBROperations(),
        ];
    }
}
