<?php

namespace Database\Seeders;

use App\Models\EBRMatrixQualitativeMitigant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EBRMatrixQualitativeMitigantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'control_type' => 'preventivo',
                'control_level' => '90',
                'periodicity' => 'por transacción (Automático)',
                'periodicity_level' => '90',
                'effectiveness' => 'alta',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'modifican las causas del riesgo.',
                'color' => '006600',
                'order' => 1,
            ],
            [
                'control_type' => 'preventivo',
                'control_level' => '90',
                'periodicity' => 'diario',
                'periodicity_level' => '72',
                'effectiveness' => 'media',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'modifican las causas del riesgo.',
                'color' => '006600',
                'order' => 2,
            ],
            [
                'control_type' => 'preventivo',
                'control_level' => '90',
                'periodicity' => 'mensual',
                'periodicity_level' => '54',
                'effectiveness' => 'media',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'modifican las causas del riesgo.',
                'color' => '006600',
                'order' => 3,
            ],
            [
                'control_type' => 'preventivo',
                'control_level' => '90',
                'periodicity' => 'anual',
                'periodicity_level' => '36',
                'effectiveness' => 'baja',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'modifican las causas del riesgo.',
                'color' => '006600',
                'order' => 4,
            ],
            [
                'control_type' => 'preventivo',
                'control_level' => '90',
                'periodicity' => 'por evento',
                'periodicity_level' => '18',
                'effectiveness' => 'baja',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'modifican las causas del riesgo.',
                'color' => '006600',
                'order' => 5,
            ],
            [
                'control_type' => 'Correctivo',
                'control_level' => '63',
                'periodicity' => 'por transacción',
                'periodicity_level' => '63',
                'effectiveness' => 'alta',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'permiten el restablecimiento de la actividad',
                'color' => 'FFC000',
                'order' => 6,
            ],
            [
                'control_type' => 'Correctivo',
                'control_level' => '63',
                'periodicity' => 'diario',
                'periodicity_level' => '50.40%',
                'effectiveness' => 'media',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'permiten el restablecimiento de la actividad',
                'color' => 'FFC000',
                'order' => 7,
            ],
            [
                'control_type' => 'Correctivo',
                'control_level' => '63',
                'periodicity' => 'mensual',
                'periodicity_level' => '37.80',
                'effectiveness' => 'media',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'permiten el restablecimiento de la actividad',
                'color' => 'FFC000',
                'order' => 8,
            ],
            [
                'control_type' => 'Correctivo',
                'control_level' => '63',
                'periodicity' => 'anual',
                'periodicity_level' => '25.20',
                'effectiveness' => 'baja',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'permiten el restablecimiento de la actividad',
                'color' => 'FFC000',
                'order' => 9,
            ],
            [
                'control_type' => 'Correctivo',
                'control_level' => '63',
                'periodicity' => 'por evento',
                'periodicity_level' => '12.60',
                'effectiveness' => 'baja',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'permiten el restablecimiento de la actividad',
                'color' => 'FFC000',
                'order' => 10,
            ],
            [
                'control_type' => 'Detectivo',
                'control_level' => '36',
                'periodicity' => 'por transacción',
                'periodicity_level' => '36',
                'effectiveness' => 'alta',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'registran un evento después de presentado.',
                'color' => 'ED7D31',
                'order' => 11,
            ],
            [
                'control_type' => 'Detectivo',
                'control_level' => '36',
                'periodicity' => 'diario',
                'periodicity_level' => '28.80',
                'effectiveness' => 'media',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'registran un evento después de presentado.',
                'color' => 'ED7D31',
                'order' => 12,
            ],
            [
                'control_type' => 'Detectivo',
                'control_level' => '36',
                'periodicity' => 'mensual',
                'periodicity_level' => '21.60',
                'effectiveness' => 'media',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'registran un evento después de presentado.',
                'color' => 'ED7D31',
                'order' => 13,
            ],
            [
                'control_type' => 'Detectivo',
                'control_level' => '36',
                'periodicity' => 'anual',
                'periodicity_level' => '14.40',
                'effectiveness' => 'baja',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'registran un evento después de presentado.',
                'color' => 'ED7D31',
                'order' => 14,
            ],
            [
                'control_type' => 'Detectivo',
                'control_level' => '36',
                'periodicity' => 'por evento',
                'periodicity_level' => '7.20',
                'effectiveness' => 'baja',
                'final_level' => 'resulta del promedio del nivel de control y el nivel de periodicidad.',
                'basis' => 'registran un evento después de presentado.',
                'color' => 'ED7D31',
                'order' => 15,
            ],
            [
                'control_type' => 'Aceptación',
                'control_level' => '10',
                'periodicity' => 'N/A',
                'periodicity_level' => '10',
                'effectiveness' => 'inexistente',
                'final_level' => '10',
                'basis' => 'cuando no se cuenta o no existe uno definido.',
                'color' => 'FF0000',
                'order' => 16,
            ],
        ];

        EBRMatrixQualitativeMitigant::truncate();
        foreach ($data as $item) {
            EBRMatrixQualitativeMitigant::create($item);
        }

    }
}
