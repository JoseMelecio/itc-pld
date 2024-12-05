<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BirthDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date_type',
        'year',
        'final_year',
        'month',
        'day',
    ];
}
