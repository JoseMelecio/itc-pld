<?php

namespace App\Exports;

use App\Models\EBRTemplateComposition;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EBROperationExport implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
    public function array(): array
    {
        $labels = EBRTemplateComposition::where('spreadsheet', 'BDdeOperaciones')
            ->orderBy('order')
            ->pluck('label')
            ->toArray();

        return [
            $labels
        ];
    }

    public function title(): string
    {
        return 'BDdeOperaciones';
    }

    public function columnWidths(): array
    {
        $columns = [];
        foreach (range('A', 'P') as $column) {
            $columns[$column] = 15;
        }

        return $columns;
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): void
    {
        $sheet->getRowDimension(1)->setRowHeight(200);

        $sheet->getStyle('A1:P1')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D9E1F2',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Borde delgado y discreto
                    'color' => ['rgb' => 'A6A6A6'],
                ],
            ],
        ]);
    }
}
