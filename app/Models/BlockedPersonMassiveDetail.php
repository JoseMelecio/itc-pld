<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedPersonMassiveDetail extends Model
{
    protected $table = 'blocked_person_massive_details';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'blocked_person_massive_id',
        'name',
        'alias',
        'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = uniqid('', true);
            }
        });
    }
}
