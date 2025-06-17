<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EBRClients extends Model
{
    use HasFactory;

    protected $table = 'ebr_clients';
    protected $fillable = [];

    public function __construct()
    {
        parent::__construct();

        $columns = EBRTemplateComposition::where('spreadsheet', 'BDdeClientes')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();

        $this->fillable = array_merge($columns, ['ebr_id', 'id']);
    }


}
