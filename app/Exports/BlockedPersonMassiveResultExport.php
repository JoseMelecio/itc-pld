<?php

namespace App\Exports;

use App\Models\BlockedPersonMassiveDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BlockedPersonMassiveResultExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle
{
    protected $massiveId;

    public function __construct($massiveId)
    {
        $this->massiveId = $massiveId;
    }

    /** 🔹 Datos del Excel */
    public function collection()
    {
        $records = BlockedPersonMassiveDetail::where(
            'blocked_person_massive_id',
            $this->massiveId
        )->get();

        $formatted = [];

        $counter = 1;
        foreach ($records as $row) {
            $formatted[] = [
                'consecutivo' => $counter++,
                'nombre'      => $row->name,
                'alias'       => $row->alias,
                'fecha'       => $row->date,
                'coincidencia' => $row->coincidence
            ];
        }

        return collect($formatted);
    }

    /** 🔹 Encabezados */
    public function headings(): array
    {
        return [
            'Consecutivo',
            'Nombre',
            'Alias',
            'Fecha Nacimiento / Constitución',
            'Coincidencia'
        ];
    }

    /** 🔹 Estilos (color de fondo + negritas) */
    public function styles(Worksheet $sheet)
    {
        // Encabezado: fondo azul navy + texto blanco y negritas
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '203764'] // Navy
            ]
        ]);

        $sheet->getColumnDimension('A')->setWidth(15); // Consecutivo
        $sheet->getColumnDimension('B')->setWidth(40); // Nombre
        $sheet->getColumnDimension('C')->setWidth(35); // Alias
        $sheet->getColumnDimension('D')->setWidth(25); // Fecha
        $sheet->getColumnDimension('E')->setWidth(20); // Coincidencia
    }

    /** 🔹 Nombre de la hoja */
    public function title(): string
    {
        return 'Resultado';
    }
}
