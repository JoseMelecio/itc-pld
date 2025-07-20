<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRMatrixQualitativeMitigant extends Model
{
    protected $table = 'ebr_matrix_qualitative_mitigants';

    protected $fillable = [
        'control_type',
        'control_level',
        'periodicity',
        'periodicity_level',
        'effectiveness',
        'final_level',
        'basis',
        'color',
        'order'
    ];
}
