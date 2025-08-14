<?php

namespace App\Exports;

use App\Models\EBR;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class EBRGraphics implements FromCollection, WithEvents, WithStyles, WithTitle
{
    protected EBR $ebr;

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
                $sheet->setTitle('Graficos');

                $data = [
                    ['X', 'Y'],
                    [1, 10],
                    [2, 20],
                    [3, 15],
                    [4, 25],
                    [5, 19],
                ];

                // Escribir los datos en la hoja (A1:B6)
                foreach ($data as $rowIndex => $row) {
                    $sheet->setCellValue('A' . ($rowIndex + 1), $row[0]);
                    $sheet->setCellValue('B' . ($rowIndex + 1), $row[1]);
                }

                // Crear gráfico
                $xAxis = [new DataSeriesValues('Number', "'Graficos'!\$A\$2:\$A\$6", null, 5)];
                $yAxis = [new DataSeriesValues('Number', "'Graficos'!\$B\$2:\$B\$6", null, 5)];

                $series = new DataSeries(
                    DataSeries::TYPE_LINECHART,
                    DataSeries::STYLE_SMOOTHMARKER,
                    [0],
                    [],
                    $xAxis,
                    $yAxis
                );

                $plotArea = new PlotArea(null, [$series]);
                $legend = new Legend(Legend::POSITION_RIGHT, null, false);
                $title = new Title('Dispersión Riesgos');

                $chart = new Chart(
                    'scatter_chart',
                    $title,
                    $legend,
                    $plotArea
                );

                // Posicionar el gráfico donde se vea
                $chart->setTopLeftPosition('A8');
                $chart->setBottomRightPosition('H25');

                $sheet->addChart($chart);

                // dump para debug
                //dd($sheet->getChartCollection());
            },
        ];
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
        return 'Graficos';
    }
}
