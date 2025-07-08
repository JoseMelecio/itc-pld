<?php

namespace Database\Seeders;

use App\Models\EBRMatrixQualitativeRisk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EBRMatrixQualitativeRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $riskData = [
            [
                "risk_level" => "alto",
                "percentage" => 95,
                "probability" => "Constante - Más del 50% de las operaciones",
                "final_value" => "constante",
                "basis" => "Mayormente la operación está expuesta",
                "color" => "FF0000",
                "order" => 1,
            ],
            [
                "risk_level" => "medio",
                "percentage" => 68,
                "probability" => "Eventual - más del 20 y menos del 50% del total de las operaciones",
                "final_value" => "eventual",
                "basis" => "Una porción de las operaciones está expuesta",
                "color" => "ED7D31",
                "order" => 2,
            ],
            [
                "risk_level" => "bajo",
                "percentage" => 41,
                "probability" => "Remoto o por Evento - más del 10 y menos del 20%",
                "final_value" => "remoto",
                "basis" => "Algunos registros de operaciones se encuentran expuestos",
                "color" => "FFC000",
                "order" => 3,
            ],
            [
                "risk_level" => "tolerancia",
                "percentage" => 10,
                "probability" => "N/A",
                "final_value" => "aceptable",
                "basis" => "Cuando el riesgo se encuentra en Tolerancia Aceptable.",
                "color" => "006600",
                "order" => 4,
            ],
            [
                "risk_level" => "fuentes externas",
                "percentage" => "N/A",
                "probability" => "N/A",
                "final_value" => "N/A",
                "basis" => "Se asigna la clasificación según la fuente externa",
                "color" => "375623",
                "order" => 5,
            ]
        ];

        EBRMatrixQualitativeRisk::truncate();
        EBRMatrixQualitativeRisk::insert($riskData);
    }
}
