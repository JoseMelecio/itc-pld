<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EBRCustomer extends Model
{
    use HasFactory;

    protected $table = 'ebr_customers';
    protected $fillable = [];

    public function __construct()
    {
        parent::__construct();

        $this->fillable = EBRTemplateComposition::where('spreadsheet', 'BDdeClientes')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();

        $this->fillable = array_merge($this->fillable, ['ebr_id', 'id']);
    }
}
