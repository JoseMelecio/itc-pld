<?php

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
        Schema::create('ebr_customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ebr_id');
            $table->string('id_client_user');
            $table->string('full_name_or_business_name');
            $table->string('client_type');
            $table->string('contract_type');
            $table->string('product_service_type');
            $table->string('gender');
            $table->string('birth_or_incorporation_date');
            $table->string('birth_state');
            $table->string('contact_phone_number');
            $table->string('phone_area_code');
            $table->string('country_of_birth');
            $table->string('nationality_country');
            $table->string('occupation_or_business_type');
            $table->string('phone');
            $table->string('residence_street_address');
            $table->string('residence_neighborhood');
            $table->string('residence_city_or_state');
            $table->string('residence_municipality');
            $table->string('residence_postal_code');
            $table->string('residence_country');
            $table->string('rfc');
            $table->string('curp');
            $table->string('email_address');
            $table->string('ip_address');
            $table->string('operating_branch_or_third_party');
            $table->string('assigned_risk_level');
            $table->string('is_pep');
            $table->string('is_related_pep');
            $table->string('reported_24h_alert');
            $table->string('blocked_person_alert');
            $table->string('is_trust');
            $table->string('is_subject_cnbv_supervised');
            $table->string('is_annex1_entity');
            $table->string('has_alert_system_flags');
            $table->string('related_to_unusual_ops');
            $table->string('related_to_internal_concern');
            $table->string('related_to_24h_ops');
            $table->string('related_to_24h_blocked_list');
            $table->string('related_to_24h_id_suspension');
            $table->string('has_coholders_or_authorized');
            $table->string('related_to_beneficiaries');
            $table->string('related_to_resource_providers');
            $table->string('declared_real_owners');
            $table->string('transfers_gafi_lists');
            $table->string('transfers_tax_havens');
            $table->string('bank_clabe');

            $table->timestamps();

            $table->foreign('ebr_id')->references('id')->on('ebrs');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_customers');
    }
};
