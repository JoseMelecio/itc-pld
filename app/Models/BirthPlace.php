<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BirthPlace extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city',
        'state_province',
        'country',
        'person_list_id'
    ];
}
