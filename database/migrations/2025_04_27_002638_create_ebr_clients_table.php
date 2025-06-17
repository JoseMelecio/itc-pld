<?php

use App\Models\EBR;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ebr_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->string('client_user_id')->nullable();
            $table->string('full_name_or_company_name')->nullable();
            $table->string('client_type')->nullable();
            $table->string('client_contract_type')->nullable();
            $table->string('type_of_product_or_service_used')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth_or_constitution')->nullable();
            $table->string('state_of_birth_or_origin')->nullable();
            $table->string('contact_phone_number')->nullable();
            $table->string('area_code_of_phone_number')->nullable();
            $table->string('country_of_birth_or_origin')->nullable();
            $table->string('nationality_country')->nullable();
            $table->string('occupation_or_business_type')->nullable();
            $table->string('phone')->nullable();
            $table->string('residence_street')->nullable();
            $table->string('residence_neighborhood')->nullable();
            $table->string('residence_city')->nullable();
            $table->string('residence_municipality_or_district')->nullable();
            $table->string('residence_postal_code')->nullable();
            $table->string('residence_country')->nullable();
            $table->string('rfc_homokey')->nullable();
            $table->string('curp')->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('branch_or_third_party_operator')->nullable();
            $table->string('assigned_risk_level')->nullable();
            $table->string('clients_pep')->nullable();
            $table->string('clients_similar_to_pep')->nullable();
            $table->string('clients_24hr_report')->nullable();
            $table->string('clients_alerted_blocked_person')->nullable();
            $table->string('clients_trusts')->nullable();
            $table->string('clients_subject_to_aml_ctf_cnbv')->nullable();
            $table->string('clients_annex_1')->nullable();
            $table->string('client_alert_system_notifications')->nullable();
            $table->string('client_related_to_unusual_operations')->nullable();
            $table->string('client_related_to_concerning_alert')->nullable();
            $table->string('client_related_to_24hrs_alert_or_operations')->nullable();
            $table->string('client_related_to_blocked_24hrs_alert_or_op')->nullable();
            $table->string('client_related_to_suspended_24hrs_alert')->nullable();
            $table->string('client_with_coholders_or_third_parties')->nullable();
            $table->string('client_related_to_beneficiaries')->nullable();
            $table->string('client_related_to_resource_suppliers')->nullable();
            $table->string('declared_real_owners')->nullable();
            $table->string('client_transfers_from_gafi')->nullable();
            $table->string('client_transfers_to_tax_havens')->nullable();
            $table->string('clabe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_clients');
    }
};
