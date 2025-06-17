<?php

namespace App\Imports;

use App\Models\EBRClients;
use App\Models\EBROperation;
use App\Models\EBRTemplateComposition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;


class EBROperationImport implements ToCollection,ShouldQueue,WithChunkReading, WithStartRow
{
    protected string $ebrId;

    public function __construct(string $ebrId)
    {
        $this->ebrId = $ebrId;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $column_var_name = EBRTemplateComposition::where('spreadsheet', 'BDdeOperaciones')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();


        foreach ($collection as $row) {
            $dataToInsert = [];
            foreach ($column_var_name as $key => $var_name) {
                $dataToInsert[$var_name] = $row[$key];
            }

            $ebrCustomer = EBRClients::where('client_user_id', $dataToInsert['client_user_id_performed_operation'])
                ->where('ebr_id', $this->ebrId)
                ->first();

            $dataToInsert['ebr_id'] = $this->ebrId;
            $dataToInsert['ebr_client_id'] = $ebrCustomer->id;
            EBROperation::create($dataToInsert);
        }

    }

    /**
     * Define el tamaño del "chunk" (filas a leer por vez).
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000; // Leer el archivo en bloques de 1000 filas
    }

    public function startRow(): int
    {
        return 3;
    }

}
