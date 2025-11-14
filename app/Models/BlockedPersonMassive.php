<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedPersonMassive extends Model
{
    protected $table = 'blocked_person_massive';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'file_uploaded',
        'download_file_name',
        'status',
        'import_done',
    ];

    protected function casts(): array
    {
        return [
            'import_done' => 'boolean'
        ];
    }
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
