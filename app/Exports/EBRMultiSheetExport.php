<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EBRMultiSheetExport implements WithMultipleSheets
{
    protected $ebr;

    public function __construct($ebr)
    {
        $this->ebr = $ebr;
    }
    public function sheets(): array
    {
        return [
            new EBRRiskZoneExport(),
            new EBRRiskInherentExport($this->ebr),
            new EBRRiskElementIndicatorExport($this->ebr),
            new EBRMatrixQualitativeRiskExport(),
            new EBRGraphics($this->ebr),
        ];
    }
}
