<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'label',
        'validation',
        'validation_message'
    ];

    protected $casts = [
        'validation_message' => 'array',
        'data_select' => 'array',
    ];

    public function pldNotices(): BelongsToMany
    {
        return $this->belongsToMany(PLDNotice::class, 'pld_notice_custom_fields');
    }
}
