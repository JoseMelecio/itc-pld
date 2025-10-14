<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PLDNoticePerson extends Model
{
    protected $table = 'pld_notice_people';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pld_notice_notice_id',
        'beneficiary',
        'person_type',
        'name_or_company',
        'paternal_last_name',
        'maternal_last_name',
        'birth_or_constitution_date',
        'tax_id',
        'personal_id',
        'nationality',
        'business_activity',
        'trust_identification',
        'pld_notice_person_id',
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
