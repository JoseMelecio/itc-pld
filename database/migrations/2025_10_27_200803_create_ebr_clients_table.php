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
            $table->string('id')->primary();
            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->string('id_usuario_cliente')->nullable();
            $table->string('nombre_completo_o_razon_social')->nullable();
            $table->string('tipo_de_cliente')->nullable();
            $table->string('tipo_de_contrato_cliente')->nullable();
            $table->string('tipo_de_producto_o_servicio_utilizado')->nullable();
            $table->string('genero')->nullable();
            $table->string('fecha_de_nacimiento_o_constitucion')->nullable();
            $table->string('estado_de_nacimiento_o_origen')->nullable();
            $table->string('numero_de_telefono_de_contacto')->nullable();
            $table->string('codigo_de_area_del_telefono')->nullable();
            $table->string('pais_de_nacimiento_o_origen')->nullable();
            $table->string('pais_de_nacionalidad')->nullable();
            $table->string('ocupacion_o_tipo_de_negocio')->nullable();
            $table->string('telefono')->nullable();
            $table->string('calle_de_residencia')->nullable();
            $table->string('colonia_de_residencia')->nullable();
            $table->string('ciudad_de_residencia')->nullable();
            $table->string('municipio_o_distrito_de_residencia')->nullable();
            $table->string('codigo_postal_de_residencia')->nullable();
            $table->string('pais_de_residencia')->nullable();
            $table->string('clave_homoclave_rfc')->nullable();
            $table->string('curp')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('direccion_ip')->nullable();
            $table->string('sucursal_o_operador_tercero')->nullable();
            $table->string('nivel_de_riesgo_asignado')->nullable();
            $table->string('clientes_pep')->nullable();
            $table->string('clientes_similares_a_pep')->nullable();
            $table->string('clientes_informes_24hrs')->nullable();
            $table->string('clientes_personas_alertadas_o_bloqueadas')->nullable();
            $table->string('clientes_fideicomisos')->nullable();
            $table->string('clientes_sujetos_a_aml_ctf_cnbv')->nullable();
            $table->string('clientes_anexo_1')->nullable();
            $table->string('notificaciones_sistema_alertas_cliente')->nullable();
            $table->string('cliente_relacionado_con_operaciones_inusuales')->nullable();
            $table->string('cliente_relacionado_con_alerta_preocupante')->nullable();
            $table->string('cliente_relacionado_con_alerta_o_operaciones_24hrs')->nullable();
            $table->string('cliente_relacionado_con_alerta_bloqueada_24hrs')->nullable();
            $table->string('cliente_relacionado_con_alerta_suspendida_24hrs')->nullable();
            $table->string('cliente_con_cotitulares_o_terceros')->nullable();
            $table->string('cliente_relacionado_con_beneficiarios')->nullable();
            $table->string('cliente_relacionado_con_proveedores_de_recursos')->nullable();
            $table->string('propietarios_reales_declarados')->nullable();
            $table->string('transferencias_cliente_desde_gafi')->nullable();
            $table->string('transferencias_cliente_hacia_paraisos_fiscales')->nullable();
            $table->string('clabe')->nullable();
            $table->timestamps();

            $table->index('ebr_id');
            $table->index('id_usuario_cliente');
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
