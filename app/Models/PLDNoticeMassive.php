<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PLDNoticeMassive extends Model
{
    protected $table = 'pld_notice_massives';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'id',
        'user_id',
        'pld_notice_id',
        'file_uploaded',
        'original_name',
        'xml_generated',
        'form_data',
        'status'
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    /**
     * @return void
     */
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
