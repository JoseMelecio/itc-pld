<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PersonListFinderImport implements ToCollection
{
    private array $data = [];

    public function collection(Collection $collection)
    {
        foreach ($collection->skip(1) as $row) {

            if (
                (empty($row[0]) || trim($row[0]) === '') &&  // Columna A
                (empty($row[1]) || trim($row[1]) === '') &&  // Columna B
                (empty($row[2]) || trim($row[2]) === '')     // Columna C
            ) {
                break;
            }

            $this->data[] = [
                'name' => $row[0],
                'alias' => $row[1],
                'date' => $row[2],
            ];
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function sheets(): array
    {
        return [
            'Busqueda' => $this,
        ];
    }
}
