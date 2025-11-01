<?php

namespace App\Exports;

use App\Models\EBR;
use App\Models\EBRConfiguration;
use App\Models\EBRRiskElement;
use App\Models\EBRRiskElementRelated;
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

class EBRRiskInherentExport implements FromCollection, WithEvents, WithStyles, WithColumnWidths, WithTitle
{
    protected EBR $ebr;
    protected $currentRow = 9;
    protected $riskElementRelated = [];

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
                $this->totalTable($sheet);
                $ebrConfiguration = EBRConfiguration::where('user_id', $this->ebr->user_id)->first();

                foreach ($ebrConfiguration->riskElements as $riskElement) {

                    $this->riskElementTable($riskElement, $sheet);
                }

            },
        ];
    }

    private function totalTable($sheet): void
    {
        $sheet->setCellValue("A2", 'Metodología con Enfoque Basado en Riesgo ' . $this->ebr->user->full_name);
        $sheet->setCellValue("D2", 'Riesgo Inherente Entidad');
        $sheet->setCellValue("A3", 'Año Enero a Diciembre ' . Carbon::now()->year);
        $sheet->setCellValue("D3", '1111.1111%');
        $sheet->setCellValue("A4", 'Monto total de Operación');
        $sheet->setCellValue("B4", 'Número de Clientes');
        $sheet->setCellValue("C4", 'Número de Operaciones');
        $sheet->setCellValue("D4", 'Concentración');
        $sheet->setCellValue("E4", 'Características Presentes');
        $sheet->setCellValue("A5", '$' . number_format($this->ebr->total_operation_amount, 2, '.', ','));
        $sheet->setCellValue("B5", $this->ebr->total_clients);
        $sheet->setCellValue("C5", number_format($this->ebr->total_operations, 0,'.', ','));
        $sheet->setCellValue("D5", '2222.2222%');
        $sheet->setCellValue("E5", '3333.3333%');
        $sheet->setCellValue("A6", 'Maximo Nivel de Riesgo');
        $sheet->setCellValue("B6", $this->ebr->maximum_risk_level);
        $sheet->setCellValue("A7", 'Riesgo Inherente');
        $sheet->setCellValue("A8", 'Elementos de Riesgo');


        $sheet->mergeCells("A2:C2");
        $sheet->getStyle("A2:C2")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 22,
                'color' => ['argb' => 'FF000066'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);
        $sheet->mergeCells("D2:E2");
        $sheet->getStyle("D2:E2")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 26,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF006666'],
            ],
        ]);
        $sheet->mergeCells("A3:C3");
        $sheet->mergeCells("D3:E3");
        $sheet->getStyle("D3:E3")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'E9FF08'],
            ],
        ]);
        $sheet->getStyle("A3:E3")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);
        $sheet->getStyle("A4:E4")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF336699'],
            ],
        ]);
        $sheet->getStyle("A5:E5")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);
        $sheet->getStyle("D5:E5")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE9FF08'],
            ],
        ]);
        $sheet->mergeCells("B6:E6");
        $sheet->getStyle("A6")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF336699'],
            ],
        ]);
        $sheet->getStyle("B6:E6")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);
        $sheet->getStyle("A2:E6")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->mergeCells("A7:I7");
        $sheet->getStyle("A7:I7")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 24,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF006666'],
            ],
        ]);
        $sheet->mergeCells("A8:I8");
        $sheet->getStyle("A8:I8")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 20,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFFFF'],
            ],
        ]);
        $sheet->getStyle("A7:I8")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }

    public function riskElementTable($riskElement, $sheet): void
    {
        $borderStart = $this->currentRow;
        $sheet->setCellValue("A{$this->currentRow}", Str::upper($riskElement->order . '. ' . $riskElement->risk_element));
        $sheet->setCellValue("B{$this->currentRow}", Str::upper($riskElement->latera_header));
        $sheet->setCellValue("E{$this->currentRow}", "CALCULAR 1.00");;
        $sheet->setCellValue("F{$this->currentRow}", "CALCULAR 1.10");
        $sheet->setCellValue("G{$this->currentRow}", "CALCULAR 1.11");

        $sheet->mergeCells("B{$this->currentRow}:D{$this->currentRow}");
        $sheet->mergeCells("H{$this->currentRow}:I{$this->currentRow}");
        $sheet->getStyle("A{$this->currentRow}:D{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF006666'],
            ],
        ]);

        $sheet->getStyle("E{$this->currentRow}:G{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF7171'],
            ],
        ]);

        $sheet->getStyle("H{$this->currentRow}:I{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF006666'],
            ],
        ]);

        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", Str::upper($riskElement->sub_header));
        $sheet->setCellValue("B{$this->currentRow}", "Importe en MXN ($) asociados (Impacto)");
        $sheet->setCellValue("C{$this->currentRow}", "Número total de Clientes");
        $sheet->setCellValue("D{$this->currentRow}", "Número de Operaciones asociadas (Frecuencia)");
        $sheet->setCellValue("E{$this->currentRow}", "Peso impacto Rango 0:100");
        $sheet->setCellValue("F{$this->currentRow}", "Frecuencia Rango 0:100");
        $sheet->setCellValue("G{$this->currentRow}", "Riesgo Inherente por Concentración");
        $sheet->setCellValue("H{$this->currentRow}", "Riesgo Inherente por Concentración");
        $sheet->setCellValue("I{$this->currentRow}", "Riesgo Inherente Integrado");

        $sheet->getStyle("A{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);

        $sheet->getStyle("B{$this->currentRow}:I{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);

        $bgBlue = $this->currentRow+1;
        foreach ($riskElement->riskElementRelated()->where('ebr_id', $this->ebr->id)->get() as $related) {
            $this->currentRow++;
            $sheet->setCellValue("A{$this->currentRow}", Str::upper($related->element));
            $sheet->setCellValue("B{$this->currentRow}", number_format($related->amount_mxn, 2, '.', ','));
            $sheet->setCellValue("C{$this->currentRow}", number_format($related->total_clients, 0,'.', ','));
            $sheet->setCellValue("D{$this->currentRow}", number_format($related->total_operations, 0,'.', ','));
            $sheet->setCellValue("E{$this->currentRow}", $related->weight_range_impact);
            $sheet->setCellValue("F{$this->currentRow}", $related->frequency_range_impact);
            $sheet->setCellValue("G{$this->currentRow}", $related->risk_inherent_concentration);
            $sheet->setCellValue("H{$this->currentRow}", $related->risk_level_features);
            $sheet->setCellValue("I{$this->currentRow}", $related->risk_level_integrated);
        }
        $this->currentRow++;

        $bgBlueLast = $this->currentRow-1;
        $sheet->getStyle("B{$bgBlue}:D{$bgBlueLast}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFBBEBF7'],
            ],
        ]);

        $sheet->getStyle("A{$borderStart}:I{$bgBlueLast}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getRowDimension($this->currentRow)->setRowHeight(30);
        $sheet->setCellValue("F{$this->currentRow}", 'Riesgo Inherente Elemento');
        $sheet->setCellValue("G{$this->currentRow}", '4444.4444%');
        $sheet->setCellValue("H{$this->currentRow}", '4444.4444%');
        $sheet->setCellValue("I{$this->currentRow}", '4444.4444%');

        $sheet->getStyle("F{$this->currentRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);

        $sheet->getStyle("G{$this->currentRow}:I{$this->currentRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                'shrinkToFit' => true,
            ],
        ]);

        $sheet->getStyle("F{$borderStart}:I{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $this->currentRow += 2;
    }

    public function styles($sheet): array
    {
        $sheet->getRowDimension(2)->setRowHeight(100);
        $sheet->getRowDimension(3)->setRowHeight(35);
        $sheet->getRowDimension(4)->setRowHeight(90);
        $sheet->getRowDimension(5)->setRowHeight(25);
        $sheet->getRowDimension(6)->setRowHeight(20);
        $sheet->getRowDimension(7)->setRowHeight(50);
        $sheet->getRowDimension(8)->setRowHeight(25);
        $sheet->getRowDimension(9)->setRowHeight(25);
        return [];
    }

    public function title(): string
    {
        return 'Elementos de Riesgo';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 60,
            'B' => 27,
            'C' => 20,
            'D' => 22,
            'E' => 25,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }
}
