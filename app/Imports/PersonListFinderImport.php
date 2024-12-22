<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PersonListFinderImport implements ToCollection
{
    private array $data = [];

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection->skip(1) as $row) {
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
            'Busqueda' => $this
        ];
    }
}
