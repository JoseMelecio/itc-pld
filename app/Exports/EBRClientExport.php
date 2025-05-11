<?php

namespace App\Exports;

use App\Models\EBRTemplateComposition;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EBRClientExport implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
    public function array(): array
    {
        $compositions = EBRTemplateComposition::where('spreadsheet', 'BDdeClientes')
            ->orderBy('order')
            ->get(['var_name', 'label']);

        $varNames = $compositions->pluck('var_name')->toArray();
        $labels = $compositions->pluck('label')->toArray();

        return [
            $varNames,
            $labels,
        ];
    }

    public function title(): string
    {
        return 'BDdeClientes';
    }

    public function columnWidths(): array
    {
        foreach (range('A', 'Z') as $column) {
            $columns[$column] = 15;
        }

        foreach (range('A', 'T') as $secondLetter) {
            $columns['A' . $secondLetter] = 15;
        }

        return $columns;
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getRowDimension(1)->setRowHeight(30)->setVisible(false);
        $sheet->getRowDimension(2)->setRowHeight(200);

        $sheet->getStyle('A1:AT2')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFD9',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'A6A6A6'],
                ],
            ],
        ]);
    }
}
