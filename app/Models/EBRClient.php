<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRClient extends Model
{
    protected $table = 'ebr_clients';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ebr_id',
        'id_usuario_cliente',
        'nombre_completo_o_razon_social',
        'tipo_de_cliente',
        'tipo_de_contrato_cliente',
        'tipo_de_producto_o_servicio_utilizado',
        'genero',
        'fecha_de_nacimiento_o_constitucion',
        'estado_de_nacimiento_o_origen',
        'numero_de_telefono_de_contacto',
        'codigo_de_area_del_telefono',
        'pais_de_nacimiento_o_origen',
        'pais_de_nacionalidad',
        'ocupacion_o_tipo_de_negocio',
        'telefono',
        'calle_de_residencia',
        'colonia_de_residencia',
        'ciudad_de_residencia',
        'municipio_o_distrito_de_residencia',
        'codigo_postal_de_residencia',
        'pais_de_residencia',
        'clave_homoclave_rfc',
        'curp',
        'correo_electronico',
        'direccion_ip',
        'sucursal_o_operador_tercero',
        'nivel_de_riesgo_asignado',
        'clientes_pep',
        'clientes_similares_a_pep',
        'clientes_informes_24hrs',
        'clientes_personas_alertadas_o_bloqueadas',
        'clientes_fideicomisos',
        'clientes_sujetos_a_aml_ctf_cnbv',
        'clientes_anexo_1',
        'notificaciones_sistema_alertas_cliente',
        'cliente_relacionado_con_operaciones_inusuales',
        'cliente_relacionado_con_alerta_preocupante',
        'cliente_relacionado_con_alerta_o_operaciones_24hrs',
        'cliente_relacionado_con_alerta_bloqueada_24hrs',
        'cliente_relacionado_con_alerta_suspendida_24hrs',
        'cliente_con_cotitulares_o_terceros',
        'cliente_relacionado_con_beneficiarios',
        'cliente_relacionado_con_proveedores_de_recursos',
        'propietarios_reales_declarados',
        'transferencias_cliente_desde_gafi',
        'transferencias_cliente_hacia_paraísos_fiscales',
        'clabe',
        'clave_cliente',
        'clave_inmueble',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = uniqid('', true);
            }
        });
    }

}
