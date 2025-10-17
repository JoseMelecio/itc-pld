<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PLDNoticeUniqueDataPerson extends Model
{
    protected $table = 'pld_notice_unique_data_people';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pld_notice_notice_id',
        'operation_date',
        'reported_operations'
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
