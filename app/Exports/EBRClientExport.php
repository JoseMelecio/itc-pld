<?php

namespace App\Exports;

use App\Models\EBRConfiguration;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EBRClientExport implements FromArray, WithTitle, WithStyles
{
    public function array(): array
    {
        $config = EBRConfiguration::where('tenant_id', auth()->user()->tenant_id)
            ->where('user_id', auth()->user()->id)
            ->first();
        return [$config->template_clients_config];
    }

    public function title(): string
    {
        return 'BDdeClientes';
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getDefaultColumnDimension()->setWidth(15);

        $sheet->getStyle('A1:AT1')->applyFromArray([
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
