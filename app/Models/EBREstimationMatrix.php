<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBREstimationMatrix extends Model
{
    protected $table = 'ebr_estimation_matrices';

    protected $fillable = [
        'matrix_type',
        'risk_level',
        'percentage_control_level',
        'probability',
        'periodicity',
        'periodicity_level',
        'effectiveness',
        'final_value',
        'basis',
    ];
}
