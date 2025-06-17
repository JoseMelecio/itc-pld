<?php

namespace App\Imports;

use App\Models\EBRClients;
use App\Models\EBRTemplateComposition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EBRClientImport implements ToCollection,ShouldQueue, WithChunkReading, WithStartRow
{
    protected string $ebrId;
    protected bool $firstChunk = true;

    public function __construct(string $ebrId)
    {
        $this->ebrId = $ebrId;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $column_var_name = EBRTemplateComposition::where('spreadsheet', 'BDdeClientes')
            ->orderBY('order')
            ->pluck('var_name')
            ->toArray();

        foreach ($collection as $row) {
            $dataToInsert = [];
            foreach ($column_var_name as $key => $var_name) {
                $dataToInsert[$var_name] = $row[$key];
            }
            $dataToInsert['ebr_id'] = $this->ebrId;
            EBRClients::create($dataToInsert);
        }
    }

    /**
     * Define el tamaño del "chunk" (filas a leer por vez).
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 50;
    }

    /**
     *
     */
    public function startRow(): int
    {
        return 3;
    }

}
