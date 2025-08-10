<?php

namespace Database\Seeders;

use App\Models\CustomField;
use App\Models\PLDNotice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateAdministrationCustomFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ocupationType = CustomField::where('name', 'occupation_type')->first();
        if (!$ocupationType) {
            $ocupationType = CustomField::create([
                'name' => 'occupation_key',
                'label' => 'Clave Ocupación',
                'validation' => 'required',
                'data_select' => [
                    '1' => 'Abogado',
                    '2' => 'Contador',
                    '3' => 'Administrador',
                    '4' => 'Outsourcing / Servicios Especializados',
                    '5' => 'Consultoría',
                    '99' => 'Otro',
                ],
                'validation_message' => ['required' => 'La clave de ocupación es obligatorio'],
            ]);
        }

        $ocupationDescription = CustomField::where('name', 'occupation_description')->first();
        if (!$ocupationDescription) {
            $ocupationDescription = CustomField::create([
                'name' => 'occupation_description',
                'label' => 'Descripción de Ocupación',
                'validation' => 'nullable|string|required_if:occupation_key,99',
                'validation_message' => 'nullable|string|required_if:occupation_key,99'
            ]);
        }
    }
}
