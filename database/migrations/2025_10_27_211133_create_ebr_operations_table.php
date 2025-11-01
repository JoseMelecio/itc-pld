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
        Schema::create('ebr_operations', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->string('folio_operacion')->nullable();
            $table->string('fecha_operacion')->nullable();
            $table->string('hora_operacion')->nullable();
            $table->string('id_usuario_cliente_que_realizo_operacion')->nullable();
            $table->string('numero_cuenta_o_contrato')->nullable();
            $table->string('tipo_de_operacion')->nullable();
            $table->string('monto_operacion')->nullable();
            $table->string('tipo_de_moneda_utilizada')->nullable();
            $table->string('monto_equivalente_mxn')->nullable();
            $table->string('tipo_cambio')->nullable();
            $table->string('instrumento_monetario')->nullable();
            $table->string('oficina_o_establecimiento')->nullable();
            $table->string('cuenta_bancaria_deposito')->nullable();
            $table->string('operacion_de_recepcion_de_recursos')->nullable();
            $table->string('estado_de_operacion')->nullable();
            $table->string('frecuencia_de_pago')->nullable();
            $table->string('numero_de_renovacion_del_contrato')->nullable();
            $table->string('canal_envio')->nullable();

            $table->timestamps();

            $table->index('ebr_id');
            $table->index('id_usuario_cliente_que_realizo_operacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_operations');
    }
};
