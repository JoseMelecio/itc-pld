<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PLDNoticeContact extends Model
{
    protected $table = 'pld_notice_contacts';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pld_notice_notice_id',
        'pld_notice_person_id',
        'country',
        'phone_number',
        'email',
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
