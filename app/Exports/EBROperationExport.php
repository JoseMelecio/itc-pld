<?php

namespace App\Exports;

use App\Models\EBRConfiguration;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EBROperationExport implements FromArray, WithTitle, WithStyles
{
    public function array(): array
    {
        $config = EBRConfiguration::where('user_id', auth()->user()->id)->first();
        return [$config->template_operations_config];
    }

    public function title(): string
    {
        return 'BDdeOperaciones';
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): void
    {
        // Altura de la primera fila
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getDefaultColumnDimension()->setWidth(15);

        // Detectar última columna y fila con datos
        $lastColumn = $sheet->getHighestColumn(); // Ej: 'P'
        $lastRow    = $sheet->getHighestRow();    // Ej: 200

        // Construir rango dinámico (desde A1 hasta última celda usada)
        $range = "A1:{$lastColumn}{$lastRow}";

        // Aplicar estilos a todo el rango detectado
        $sheet->getStyle($range)->applyFromArray([
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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'A6A6A6'],
                ],
            ],
        ]);
    }
}
