<?php

namespace App\Exports;

use App\Models\EBRMatrixQualitativeMitigant;
use App\Models\EBRMatrixQualitativeRisk;
use App\Models\EBRRiskZone;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EBRMatrixQualitativeRiskExport implements FromCollection, WithEvents, WithColumnWidths, WithTitle
{
    protected int $currentRow = 2;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->riskQualitativeTable($sheet);
                $this->riskMitigantsTable($sheet);
            },
        ];
    }

    public function  riskQualitativeTable($sheet): void
    {
        $sheet->setCellValue("A{$this->currentRow}", 'Matriz de Estimacion del Peso de Riesgo Cualitativos');
        $sheet->mergeCells("A{$this->currentRow}:E{$this->currentRow}");

        $sheet->getStyle("A{$this->currentRow}:E{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => "FFFFFFFF"],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => "FF009999"],
            ],
        ]);
        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", 'Nivel de Riesgo');;
        $sheet->setCellValue("B{$this->currentRow}", 'Porcentaje');
        $sheet->setCellValue("C{$this->currentRow}", 'Probabilidad');
        $sheet->setCellValue("D{$this->currentRow}", 'Valor Final');
        $sheet->setCellValue("E{$this->currentRow}", 'Fundameto');

        $sheet->getStyle("A{$this->currentRow}:E{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => "FFFFFFFF"],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => "FF002060"],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $this->currentRow++;

        $matrix = EBRMatrixQualitativeRisk::orderBy('order', 'ASC')->get()->toArray();
        foreach ($matrix as $element) {
            $sheet->setCellValue("A{$this->currentRow}", Str::ucfirst($element['risk_level']));
            $sheet->setCellValue("B{$this->currentRow}", Str::ucfirst($element['percentage']));
            $sheet->setCellValue("C{$this->currentRow}", Str::ucfirst($element['probability']));
            $sheet->setCellValue("D{$this->currentRow}", Str::ucfirst($element['final_value']));
            $sheet->setCellValue("E{$this->currentRow}", Str::ucfirst($element['basis']));

            $sheet->getStyle("A{$this->currentRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 9,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => "FF{$element['color']}"],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                    'shrinkToFit' => false,
                ],
            ]);
            $sheet->getStyle("B{$this->currentRow}:E{$this->currentRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 9,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                    'shrinkToFit' => false,
                ],
            ]);
            $this->currentRow++;
        }

        $this->currentRow -= 1;
        $sheet->getStyle("A2:E{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A4:A{$this->currentRow}")->applyFromArray([
            'font' => [
                'color' => ['argb' => 'FFFFFFFF'],
                'bold' => true,
            ],
        ]);
    }

    public function  riskMitigantsTable($sheet): void
    {
        $this->currentRow += 3;
        $initialMitigantRow = $this->currentRow;
        $sheet->setCellValue("A{$this->currentRow}", 'Matriz de Estimacion del Peso de Mitigantes Cualitativos');
        $sheet->mergeCells("A{$this->currentRow}:G{$this->currentRow}");

        $sheet->getStyle("A{$this->currentRow}:G{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => "FFFFFFFF"],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => "FF009999"],
            ],
        ]);
        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", 'Tipo de Control');
        $sheet->setCellValue("B{$this->currentRow}", 'Nivel de Control ');
        $sheet->setCellValue("C{$this->currentRow}", 'Periodicidad');
        $sheet->setCellValue("D{$this->currentRow}", 'Nivel de Periodicidad');
        $sheet->setCellValue("E{$this->currentRow}", 'Eficacia');
        $sheet->setCellValue("F{$this->currentRow}", 'Valor Final');
        $sheet->setCellValue("G{$this->currentRow}", 'Fundameto');

        $sheet->getStyle("A{$this->currentRow}:G{$this->currentRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => "FFFFFFFF"],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => "FF002060"],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $this->currentRow++;

        $matrix = EBRMatrixQualitativeMitigant::orderBy('order', 'ASC')->get()->toArray();
        foreach ($matrix as $element) {
            $sheet->setCellValue("A{$this->currentRow}", Str::ucfirst($element['control_type']));
            $sheet->setCellValue("B{$this->currentRow}", Str::ucfirst($element['control_level']));
            $sheet->setCellValue("C{$this->currentRow}", Str::ucfirst($element['periodicity']));
            $sheet->setCellValue("D{$this->currentRow}", Str::ucfirst($element['periodicity_level']));
            $sheet->setCellValue("E{$this->currentRow}", Str::ucfirst($element['effectiveness']));
            $sheet->setCellValue("F{$this->currentRow}", Str::ucfirst($element['final_level']));
            $sheet->setCellValue("G{$this->currentRow}", Str::ucfirst($element['basis']));

            $sheet->getStyle("A{$this->currentRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 9,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => "FF{$element['color']}"],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                    'shrinkToFit' => false,
                ],
            ]);
            $sheet->getStyle("B{$this->currentRow}:G{$this->currentRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 9,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                    'shrinkToFit' => false,
                ],
            ]);
            $this->currentRow++;
        }

        $this->currentRow -= 1;
        $sheet->getStyle("A{$initialMitigantRow}:G{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $header = $initialMitigantRow+2;
        $sheet->getStyle("A4:A{$this->currentRow}")->applyFromArray([
            'font' => [
                'color' => ['argb' => 'FFFFFFFF'],
                'bold' => true,
            ],
        ]);
    }

    public function title(): string
    {
        return 'Estimacion Cualitativa';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 18,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 18,
            'G' => 18,
        ];
    }
}
