<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PLDNoticeNotice extends Model
{
    protected $table = 'pld_notice_notices';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'hash',
        'pld_notice_id',
        'reference',
        'priority',
        'alert_type',
        'alert_description',
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

    public function objectPerson(): HasOne
    {
        return $this->hasOne(PLDNoticePerson::class, 'pld_notice_notice_id', 'id')
            ->where('person_notice_type', 'object');
    }

    public function beneficiaryPerson(): hasOne
    {
        return $this->hasOne(PLDNoticePerson::class, 'pld_notice_notice_id', 'id')
            ->where('person_notice_type', 'beneficiary');
    }

    public function legalRepresentativePerson(): hasOne
    {
        return $this->hasOne(PLDNoticePerson::class, 'pld_notice_notice_id', 'id');
    }

    public function contact(): hasOne
    {
        return $this->hasOne(PLDNoticeContact::class, 'pld_notice_notice_id', 'id');
    }

    public function address(): hasOne
    {
        return $this->hasOne(PLDNoticeAddress::class, 'pld_notice_notice_id', 'id');
    }

    public function uniqueData(): hasOne
    {
        return $this->hasOne(PLDNoticeUniqueDataPerson::class, 'pld_notice_notice_id', 'id');
    }

    public function financialOperation(): hasMany
    {
        return $this->hasMany(PLDNoticeFinancialOperation::class, 'pld_notice_notice_id', 'id');
    }

    public function estate(): hasMany
    {
        return $this->hasMany(PLDNoticeEstate::class, 'pld_notice_notice_id', 'id');
    }
}
