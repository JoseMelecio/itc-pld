<?php

namespace App\Imports;

use App\Models\EBRCustomer;
use App\Models\EBRTemplateComposition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;use Maatwebsite\Excel\Concerns\WithChunkReading;


class EBRCustomerImport implements ToCollection,ShouldQueue, WithChunkReading
{
    protected string $ebrUUID;

    public function __construct(string $ebrUUID)
    {
        $this->ebrUUID = $ebrUUID;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $dataReaded = $collection->skip(1);

        $column_var_name = EBRTemplateComposition::where('spreadsheet', 'BDdeClientes')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();


        foreach ($dataReaded as $row) {
            $dataToInsert = [];
            foreach ($column_var_name as $key => $var_name) {
                $dataToInsert[$var_name] = $row[$key];
            }
            $dataToInsert['id'] = Str::uuid();
            $dataToInsert['ebr_id'] = $this->ebrUUID;
            EbrCustomer::create($dataToInsert);
        }
    }

    /**
     * Define el tamaño del "chunk" (filas a leer por vez).
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

}
