<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];
}
