<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBROperation extends Model
{
    protected $table = 'ebr_operations';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ebr_id',
        'folio_operacion',
        'fecha_operacion',
        'hora_operacion',
        'id_usuario_cliente_que_realizo_operacion',
        'numero_cuenta_o_contrato',
        'tipo_de_operacion',
        'monto_operacion',
        'tipo_de_moneda_utilizada',
        'monto_equivalente_mxn',
        'tipo_cambio',
        'instrumento_monetario',
        'oficina_o_establecimiento',
        'cuenta_bancaria_deposito',
        'operacion_de_recepcion_de_recursos',
        'estado_de_operacion',
        'frecuencia_de_pago',
        'numero_de_renovacion_del_contrato',
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
