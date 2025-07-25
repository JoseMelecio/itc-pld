<?php

namespace Database\Seeders;

use App\Models\CustomField;
use App\Models\PLDNotice;
use Illuminate\Database\Seeder;

class VirtualAssetsCustomFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platformDomain = CustomField::create([
            'name' => 'platform_domain',
            'label' => 'Dominio de la plataforma',
            'validation' => 'required',
            'validation_message' => ['required' => 'El dominio de las plataforma es obligatorio'],
        ]);


        $notices = PLDNotice::where('route_param', 'virtual_assets')->get();
        foreach ($notices as $notice) {
            $notice->customFields()->attach($platformDomain->id);
        }
    }
}
