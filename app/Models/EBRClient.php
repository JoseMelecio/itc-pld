<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class EBRClient extends Model
{
    use HasFactory;

    protected $connection='mongodb';
    protected $table = 'ebr_clients';

    protected $fillable = [];

}
