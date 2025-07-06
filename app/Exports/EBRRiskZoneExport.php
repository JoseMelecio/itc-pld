<?php

namespace App\Exports;

use App\Models\EBRRiskZone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Str;


class EBRRiskZoneExport implements FromCollection, WithEvents, WithColumnWidths, WithTitle
{
    protected int $currentRow = 1;

    public function collection(): \Illuminate\Support\Collection
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->riskZoneTable($sheet);
            },
        ];
    }

    public function  riskZoneTable($sheet): void
    {
        $sheet->setCellValue("A{$this->currentRow}", 'Entidad Federativa');
        $sheet->setCellValue("B{$this->currentRow}", 'Incidencia Delictiva');
        $sheet->setCellValue("C{$this->currentRow}", 'Porcentaje 1');
        $sheet->setCellValue("D{$this->currentRow}", 'Porcentaje 2');
        $sheet->setCellValue("E{$this->currentRow}", 'Zona de riesgo');

        $sheet->getStyle("A1:E1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $this->currentRow++;

        $zones = EBRRiskZone::orderBy('zone', 'ASC')->orderBy('risk_zone')->get()->toArray();
        foreach ($zones as $zone) {
            $sheet->setCellValue("A{$this->currentRow}", Str::ucfirst($zone['risk_zone'] . "%"));
            $sheet->setCellValue("B{$this->currentRow}", Str::ucfirst($zone['incidence_of_crime'] . "%"));
            $sheet->setCellValue("C{$this->currentRow}", Str::ucfirst($zone['percentage_1'] . "%"));
            $sheet->setCellValue("D{$this->currentRow}", Str::ucfirst($zone['percentage_2'] . "%"));
            $sheet->setCellValue("E{$this->currentRow}", Str::ucfirst($zone['zone'] . "%"));

            $sheet->getStyle("A{$this->currentRow}:E{$this->currentRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => "FF{$zone['color']}"],
                ],
            ]);
            $this->currentRow++;
        }
    }

    public function title(): string
    {
        return 'Zonas de Riesgo';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 15
        ];
    }
}
