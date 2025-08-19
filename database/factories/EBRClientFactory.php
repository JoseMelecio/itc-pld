<?php

namespace Database\Factories;

use App\Models\EBRClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EBRClient>
 */
class EBRClientFactory extends Factory
{
    protected $model = EBRClient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ebr_id' => 1, // asegúrate que exista en la tabla ebrs
            'client_user_id' => $this->faker->uuid(),
            'full_name_or_company_name' => $this->faker->company(),
            'client_type' => $this->faker->randomElement(['person', 'company']),
            'client_contract_type' => $this->faker->randomElement(['standard', 'premium']),
            'type_of_product_or_service_used' => $this->faker->word(),
            'gender' => $this->faker->randomElement(['male', 'female', null]),
            'date_of_birth_or_constitution' => $this->faker->date(),
            'state_of_birth_or_origin' => $this->faker->state(),
            'contact_phone_number' => $this->faker->phoneNumber(),
            'area_code_of_phone_number' => $this->faker->areaCode(),
            'country_of_birth_or_origin' => $this->faker->countryCode(),
            'nationality_country' => $this->faker->countryCode(),
            'occupation_or_business_type' => $this->faker->jobTitle(),
            'phone' => $this->faker->phoneNumber(),
            'residence_street' => $this->faker->streetAddress(),
            'residence_neighborhood' => $this->faker->citySuffix(),
            'residence_city' => $this->faker->city(),
            'residence_municipality_or_district' => $this->faker->city(),
            'residence_postal_code' => $this->faker->postcode(),
            'residence_country' => $this->faker->countryCode(),
            'rfc_homokey' => strtoupper($this->faker->bothify('????######??')),
            'curp' => strtoupper($this->faker->bothify('????######??????##')),
            'email' => $this->faker->unique()->safeEmail(),
            'ip_address' => $this->faker->ipv4(),
            'branch_or_third_party_operator' => $this->faker->company(),
            'assigned_risk_level' => $this->faker->randomElement(['low', 'medium', 'high']),
            'clients_pep' => $this->faker->randomElement(['yes', 'no']),
            'clients_similar_to_pep' => $this->faker->randomElement(['yes', 'no']),
            'clients_24hr_report' => $this->faker->randomElement(['yes', 'no']),
            'clients_alerted_blocked_person' => $this->faker->randomElement(['yes', 'no']),
            'clients_trusts' => $this->faker->word(),
            'clients_subject_to_aml_ctf_cnbv' => $this->faker->randomElement(['yes', 'no']),
            'clients_annex_1' => $this->faker->word(),
            'client_alert_system_notifications' => $this->faker->sentence(),
            'client_related_to_unusual_operations' => $this->faker->randomElement(['yes', 'no']),
            'client_related_to_concerning_alert' => $this->faker->randomElement(['yes', 'no']),
            'client_related_to_24hrs_alert_or_operations' => $this->faker->randomElement(['yes', 'no']),
            'client_related_to_blocked_24hrs_alert_or_op' => $this->faker->randomElement(['yes', 'no']),
            'client_related_to_suspended_24hrs_alert' => $this->faker->randomElement(['yes', 'no']),
            'client_with_coholders_or_third_parties' => $this->faker->randomElement(['yes', 'no']),
            'client_related_to_beneficiaries' => $this->faker->randomElement(['yes', 'no']),
            'client_related_to_resource_suppliers' => $this->faker->randomElement(['yes', 'no']),
            'declared_real_owners' => $this->faker->name(),
            'client_transfers_from_gafi' => $this->faker->randomElement(['yes', 'no']),
            'client_transfers_to_tax_havens' => $this->faker->randomElement(['yes', 'no']),
            'clabe' => $this->faker->numerify('###################'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
