<?php

namespace App\Imports;

use App\Jobs\FinalizeEBRProcessingJob;
use App\Models\EBROperation;
use App\Models\EBRTemplateComposition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class EBROperationImport implements ToCollection,ShouldQueue,WithChunkReading, WithStartRow, WithEvents
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

        $bulkInsert = [];

        foreach ($collection as $row) {
            $dataToInsert = [];
            foreach ($column_var_name as $key => $var_name) {
                $dataToInsert[$var_name] = $row[$key];
            }

            $dataToInsert['ebr_id'] = $this->ebrId;
            $bulkInsert[] = $dataToInsert;
        }

        EBROperation::insert($bulkInsert);
    }

    /**
     * Define el tamaño del "chunk" (filas a leer por vez).
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 3;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                FinalizeEBRProcessingJob::dispatch($this->ebrId);
            },
        ];
    }

}
