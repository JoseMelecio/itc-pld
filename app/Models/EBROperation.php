<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EBROperation extends Model
{
    use HasFactory;

    protected $table = 'ebr_operations';
    protected $fillable = [];

    public function __construct()
    {
        parent::__construct();

        $columns= EBRTemplateComposition::where('spreadsheet', 'BDdeOperaciones')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();

        $this->fillable = array_merge($columns, ['id', 'ebr_id', 'ebr_client_id']);
    }

}
