<?php

namespace Database\Seeders;

use App\Models\EBREstimationMatrix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EBREstimationMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ebr_estimation_matrices')->truncate();

        //Riesgos Cualitativos Inherente.
        $percentage = 95;
        $matrixType = 'Matriz estimación de peso para Riesgos Cualitativos Inherente.';

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Alto',
            'percentage_control_level' => $percentage,
            'probability' => 'Constante - Más del 50% de las operaciones',
            'final_value' => 'Constante',
            'basis' => 'Mayormente la operación está expuesta',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Medio',
            'percentage_control_level' => $percentage-27,
            'probability' => 'Eventual - más del 20 y menos del 50% del total de las operaciones',
            'final_value' => 'Eventual',
            'basis' => 'Una porción de las operaciones está expuesta',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Bajo',
            'percentage_control_level' => $percentage-54,
            'probability' => 'Remoto o por Evento - más del 10 y menos del 20%',
            'final_value' => 'Remoto',
            'basis' => 'Algunos registros de operaciones se encuentran expuestos',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Tolerancia',
            'percentage_control_level' => 15,
            'probability' => 'N/A',
            'final_value' => 'Aceptable',
            'basis' => 'Cuando el riesgo se encuentra en Tolerancia Aceptable',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Fuentes Externas',
            'percentage_control_level' => null,
            'probability' => 'N/A',
            'final_value' => 'N/A',
            'basis' => 'Se asigna la clasificación según la fuente externa',
        ]);

        //Peso para mitigantes cualitativos
        //Preventido
        $controlLevel = 90;
        $matrixType = 'Matriz de estimación de peso para Mitigantes Cualitativos';

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Preventivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Por transacción',
            'periodicity_level' => $controlLevel,
            'effectiveness' => 'Alto',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Modifican las causas del Riesgo',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Preventivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Diario',
            'periodicity_level' => ($controlLevel/5)*4,
            'effectiveness' => 'Media',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Modifican las causas del Riesgo',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Preventivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Mensual',
            'periodicity_level' => ($controlLevel/5)*3,
            'effectiveness' => 'Media',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Modifican las causas del Riesgo',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Preventivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Anual',
            'periodicity_level' => ($controlLevel/5)*2,
            'effectiveness' => 'Baja',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Modifican las causas del Riesgo',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Preventivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Por Evento',
            'periodicity_level' => ($controlLevel/5),
            'effectiveness' => 'Baja',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Modifican las causas del Riesgo',
        ]);

        //Correctivo
        $controlLevel = 65;
        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Correctivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Por transacción',
            'periodicity_level' => $controlLevel,
            'effectiveness' => 'Alto',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Permiten el restablecimiento de la actividad',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Correctivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Diario',
            'periodicity_level' => ($controlLevel/5)*4,
            'effectiveness' => 'Media',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Permiten el restablecimiento de la actividad',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Correctivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Mensual',
            'periodicity_level' => ($controlLevel/5)*3,
            'effectiveness' => 'Media',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Permiten el restablecimiento de la actividad',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Correctivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Anual',
            'periodicity_level' => ($controlLevel/5)*2,
            'effectiveness' => 'Baja',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Permiten el restablecimiento de la actividad',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Correctivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Por Evento',
            'periodicity_level' => ($controlLevel/5),
            'effectiveness' => 'Baja',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Permiten el restablecimiento de la actividad',
        ]);

        //Detectivo
        $controlLevel = 40;
        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Detectivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Por transacción',
            'periodicity_level' => $controlLevel,
            'effectiveness' => 'Alto',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Regitran un evento después de presentado',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Detectivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Diario',
            'periodicity_level' => ($controlLevel/5)*4,
            'effectiveness' => 'Media',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Regitran un evento después de presentado',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Detectivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Mensual',
            'periodicity_level' => ($controlLevel/5)*3,
            'effectiveness' => 'Media',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Regitran un evento después de presentado',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Detectivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Anual',
            'periodicity_level' => ($controlLevel/5)*2,
            'effectiveness' => 'Baja',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Regitran un evento después de presentado',
        ]);

        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Detectivo',
            'percentage_control_level' => $controlLevel,
            'periodicity' => 'Por Evento',
            'periodicity_level' => ($controlLevel/5),
            'effectiveness' => 'Baja',
            'final_value' => 'Resulta del promedio del Nivel de Control y el Nivel de Periodicidad',
            'basis' => 'Regitran un evento después de presentado',
        ]);

        // Aceptación
        EBREstimationMatrix::create([
            'matrix_type' => $matrixType,
            'risk_level_control_type' => 'Aceptación',
            'percentage_control_level' => 15,
            'periodicity' => 'N/A',
            'periodicity_level' => null,
            'effectiveness' => 'Inexistente',
            'final_value' => '15',
            'basis' => 'Cuando no se cuenta o no existe uno definido',
        ]);
    }
}
