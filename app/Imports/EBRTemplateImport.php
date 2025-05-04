<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EBRTemplateImport implements WithMultipleSheets, ShouldQueue, WithChunkReading
{
    protected string $ebrUUID;

    /**
     * Constructor para recibir el UUID del modelo EBR
     *
     * @param string $ebrUUID
     */
    public function __construct(string $ebrUUID)
    {
        $this->ebrUUID = $ebrUUID;
    }
    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => new EBRCustomerImport($this->ebrUUID),
            1 => new EBROperationImport($this->ebrUUID),
        ];
    }

    /**
     * Define el tamaño del "chunk" (filas a leer por vez).
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 100; // Leer el archivo en bloques de 1000 filas
    }

}
