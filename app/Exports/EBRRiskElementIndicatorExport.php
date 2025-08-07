<?php

namespace App\Exports;

use App\Models\EBR;
use App\Models\EBRRiskElementIndicatorRelated;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;

class EBRRiskElementIndicatorExport implements FromCollection, WithEvents, WithStyles, WithColumnWidths, WithTitle
{
    protected EBR $ebr;
    protected $currentRow = 2;

    public function __construct(EBR $ebr)
    {
        $this->ebr = $ebr;
    }

    public function collection(): Collection
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach ($this->ebr->type->riskElements as $riskElement) {
                    $this->riskIndicatorTable($sheet, $riskElement);
                }
            },
        ];
    }

    private function riskIndicatorTable($sheet, $riskElement): void
    {
        $sheet->setCellValue("A{$this->currentRow}", "Indicadores de Riesgo del Elemento: {$riskElement->risk_element}");
        $sheet->mergeCells("A{$this->currentRow}:K{$this->currentRow}");

        $sheet->getStyle("A{$this->currentRow}:K{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => false,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF44546A'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);
        $this->currentRow++;

        $sheet->setCellValue("A{$this->currentRow}", 'Caracteristicas');
        $sheet->setCellValue("B{$this->currentRow}", 'Clave');
        $sheet->setCellValue("C{$this->currentRow}", 'Nombre de la Caracteristica');
        $sheet->setCellValue("D{$this->currentRow}", 'Descripcion de la Caracteristica');
        $sheet->setCellValue("E{$this->currentRow}", 'Tipo de Indicador de Riesgo LD y/o FT');
        $sheet->setCellValue("F{$this->currentRow}", 'Importe en MXN relacionadas');
        $sheet->setCellValue("G{$this->currentRow}", 'Numero de Clientes Relacionados');
        $sheet->setCellValue("H{$this->currentRow}", 'Numero de Operaciones Relacionadas');
        $sheet->setCellValue("I{$this->currentRow}", 'Peso Impacto Rango 0:100');
        $sheet->setCellValue("J{$this->currentRow}", 'Frecuencia Rango 0:100');
        $sheet->setCellValue("K{$this->currentRow}", 'Concentracion Caracteristica');

        $sheet->getStyle("A{$this->currentRow}:K{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A{$this->currentRow}:K{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF00CC99'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);
        $this->currentRow++;

        $indicatorsRelated = EBRRiskElementIndicatorRelated::where('ebr_risk_element_indicator_id', $riskElement->id)
            ->where('ebr_id', $this->ebr->id)
            ->orderBy('order')->get();

        foreach ($indicatorsRelated as $indicator) {
            $sheet->setCellValue("A{$this->currentRow}", $indicator->characteristic);
            $sheet->setCellValue("B{$this->currentRow}", $indicator->key);
            $sheet->setCellValue("C{$this->currentRow}", $indicator->name);
            $sheet->setCellValue("D{$this->currentRow}", $indicator->description);
            $sheet->setCellValue("E{$this->currentRow}", $indicator->risk_indicator);
            $sheet->setCellValue("F{$this->currentRow}", number_format($indicator->amount, 2, '.', ','));
            $sheet->setCellValue("G{$this->currentRow}", number_format($indicator->related_clients, 0, '.', ','));
            $sheet->setCellValue("H{$this->currentRow}", number_format($indicator->related_operations, 0, '.', ','));
            $sheet->setCellValue("I{$this->currentRow}", "$indicator->weight_range_impact%");
            $sheet->setCellValue("J{$this->currentRow}", "$indicator->frequency_range_impact%");
            $sheet->setCellValue("K{$this->currentRow}", "$indicator->characteristic_concentration%");

            $sheet->getStyle("I{$this->currentRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $this->heatColor($indicator->weight_range_impact)],
                ],
            ]);

            $sheet->getStyle("J{$this->currentRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $this->heatColor($indicator->frequency_range_impact)],
                ],
            ]);

            $sheet->getStyle("k{$this->currentRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $this->heatColor($indicator->characteristic_concentration)],
                ],
            ]);

            $sheet->getStyle("A{$this->currentRow}:K{$this->currentRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $sheet->getStyle("F{$this->currentRow}:K{$this->currentRow}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $this->currentRow++;
        }

        $this->currentRow++;
    }

    public function styles($sheet): array
    {
//        $sheet->getRowDimension(2)->setRowHeight(100);
//        $sheet->getRowDimension(3)->setRowHeight(35);
//        $sheet->getRowDimension(4)->setRowHeight(90);
//        $sheet->getRowDimension(5)->setRowHeight(25);
//        $sheet->getRowDimension(6)->setRowHeight(20);
//        $sheet->getRowDimension(7)->setRowHeight(50);
//        $sheet->getRowDimension(8)->setRowHeight(25);
//        $sheet->getRowDimension(9)->setRowHeight(25);
        return [];
    }

    public function title(): string
    {
        return 'Indicadores de Riesgo';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 60,
            'B' => 17,
            'C' => 20,
            'D' => 22,
            'E' => 25,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }

    public function heatColor(float $number): string
    {
        if ($number < 20) {
            return 'FF44BF5D';
        }

        if ($number < 40) {
            return 'FF44BF5D';
        }

        if ($number < 60) {
            return 'FFFFE283';
        }

        if ($number < 80) {
            return 'FFFFAC7B';
        }

        if ($number < 100) {
            return 'FFFF7171';
        }

        return 'FF0000';
    }

}

