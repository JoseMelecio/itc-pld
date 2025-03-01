<?php

namespace Database\Seeders;

use App\Models\CustomField;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class AdminBankAccountCustomFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customTypeOccupation = CustomField::create([
            'name' => 'occupation_type',
            'label' => 'Tipo de ocupación',
            'validation' => 'required',
            'validation_message' => ['required' => 'El tipo de ocupación es obligatorio'],
            'data_select' => [
                '1' => 'Abogado',
                '2' => 'Contador',
                '3' => 'Administrador',
                '4' => 'Outsourcing / Servicios Especializados',
                '5' => 'Consultoría',
                '99' => 'Otro',
            ],
        ]);

        $customOccupationDescription = CustomField::create([
            'name' => 'occupation_description',
            'label' => 'Descripcion de la ocupacion',
            'validation' => 'nullable|string|required_if:type_occupation,99',
            'validation_message' => ['required_if' => 'La descripción de la obligatoria cuando el tipo de ocupación es Otro'],
        ]);

        $notice = PLDNotice::where('route_param', 'bank_account_management')->first();
        $notice->customFields()->attach($customTypeOccupation->id);
        $notice->customFields()->attach($customOccupationDescription->id);

    }
}
