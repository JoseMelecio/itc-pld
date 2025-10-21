<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class EBR extends Model
{
    use HasFactory;

    protected $table = 'ebrs';

    protected $fillable = [
        'id',
        'user_id',
        'file_name_clients',
        'file_name_operations',
        'ebr_type_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(EBRClient::class, 'ebr_id');
    }

    public function type(): HasOne
    {
        return $this->hasOne(EBRType::class, 'id', 'ebr_type_id');
    }

    public function riskElementsRelated(): HasMany
    {
        return $this->hasMany(EBRRiskElementRelated::class, 'ebr_id');
    }

    public function getTotalAmountAttribute()
    {
        return DB::table('ebr_operations')->where('ebr_id', $this->id)->sum('operation_amount');
    }

    public function getTotalClientsCountAttribute(): int
    {
        return $this->clients()->count();
    }

    public function getTotalOperationsCountAttribute(): int
    {
        return DB::table('ebr_operations')->where('ebr_id', $this->id)->count();
    }

    public function getMaximumRiskLevelCountAttribute(): int
    {
        return 100;
    }

}
