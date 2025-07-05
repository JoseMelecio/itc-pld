<?php

namespace Database\Seeders;

use App\Models\EBRRiskElement;
use App\Models\EBRType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SofomRiskElementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ebrType = EBRType::where('type', 'sofom')->first();
        EBRRiskElement::where('ebr_type_id', $ebrType->id)->delete();

        EBRRiskElement::create([
            'ebr_type_id' => $ebrType->id,
            'order' => 1,
            'risk_element' => 'tipo de clientes.',
            'lateral_header' => 'peso indicadores y elemento',
            'sub_header' => 'tipo de persona jurídica',
            'entity_grouper' => 'clients',
            'variable_grouper' => 'client_type',
            'active' => 1,
        ]);

        EBRRiskElement::create([
            'ebr_type_id' => $ebrType->id,
            'order' => 2,
            'risk_element' => 'productos y servicios.',
            'lateral_header' => 'peso indicadores y elemento',
            'sub_header' => 'productos y servicios que ofrece la institución.',
            'entity_grouper' => 'clients',
            'variable_grouper' => 'type_of_product_or_service_used',
            'active' => 1,
        ]);

        EBRRiskElement::create([
            'ebr_type_id' => $ebrType->id,
            'order' => 3,
            'risk_element' => 'transacciones.',
            'lateral_header' => 'peso indicadores y elemento',
            'sub_header' => 'instrumentos de pago y medios de pago.',
            'entity_grouper' => 'operations',
            'variable_grouper' => 'monetary_instrument',
            'active' => 1,
        ]);

        EBRRiskElement::create([
            'ebr_type_id' => $ebrType->id,
            'order' => 4,
            'risk_element' => 'canales de envío.',
            'lateral_header' => 'peso indicadores y elemento',
            'sub_header' => 'presenciales o no presenciales.',
            'entity_grouper' => 'clients',
            'variable_grouper' => 'nationality_country',
            'active' => 1,
        ]);

        EBRRiskElement::create([
            'ebr_type_id' => $ebrType->id,
            'order' => 5,
            'risk_element' => 'areas geograficas.',
            'lateral_header' => 'peso indicadores y elemento',
            'sub_header' => '2.1. Por Incidencia delictiva nacional y otros indicadores.',
            'entity_grouper' => 'clients',
            'variable_grouper' => 'state_of_birth_or_origin',
            'active' => 1,
        ]);
    }
}
