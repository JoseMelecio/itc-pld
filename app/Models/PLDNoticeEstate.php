<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PLDNoticeEstate extends Model
{
    protected $table = 'pld_notice_estates';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pld_notice_notice_id',
        'pld_notice_person_id',
        'estate',
        'reference_value',
        'state',
        'city',
        'settlement',
        'postal_code',
        'street',
        'external_number',
        'internal_number',
        'real_folio',
    ];
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = uniqid();
            }
        });
    }
}
