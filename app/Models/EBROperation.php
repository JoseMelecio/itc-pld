<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EBROperation extends Model
{
    use HasFactory;

    protected $table = 'ebr_operations';
    protected $fillable = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function __construct()
    {
        parent::__construct();

        $this->fillable = EBRTemplateComposition::where('spreadsheet', 'BDdeOperaciones')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();

        $this->fillable = array_merge($this->fillable, ['ebr_id', 'id', 'ebr_customer_id']);
    }

}
