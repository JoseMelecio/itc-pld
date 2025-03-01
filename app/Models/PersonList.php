<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonList extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'origin',
        'record_type',
        'un_list_type',
        'first_name',
        'second_name',
        'third_name',
        'file',
        'file_name_from_import',
    ];

    public function aliases(): HasMany
    {
        return $this->hasMany(Alias::class);
    }

    public function birthPlaces(): HasMany
    {
        return $this->hasMany(BirthPlace::class);
    }

    public function birthDates(): HasMany
    {
        return $this->hasMany(BirthDate::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function nationalities(): HasMany
    {
        return $this->hasMany(Nationality::class);
    }
}
