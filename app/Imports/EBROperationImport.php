<?php

namespace App\Imports;

use App\Models\EBRCustomer;
use App\Models\EBROperation;
use App\Models\EBRTemplateComposition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class EBROperationImport implements ToCollection,ShouldQueue,WithChunkReading
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
        $dataReaded = $collection->skip(1);

        $column_var_name = EBRTemplateComposition::where('spreadsheet', 'BDdeOperaciones')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();


        foreach ($dataReaded as $row) {
            $dataToInsert = [];
            foreach ($column_var_name as $key => $var_name) {
                $dataToInsert[$var_name] = $row[$key];
            }

            $ebrCustomer = EBRCustomer::where('id_client_user', $dataToInsert['client_user_id'])
                ->where('ebr_id', $this->ebrUUID)
                ->first();

            $dataToInsert['id'] = Str::uuid();
            $dataToInsert['ebr_id'] = $this->ebrUUID;
            $dataToInsert['ebr_customer_id'] = $ebrCustomer->id;

            Log::info($dataToInsert);

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

}
