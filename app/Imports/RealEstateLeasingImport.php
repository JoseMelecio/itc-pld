<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RealEstateLeasingImport implements ToCollection
{
    private $data = [];
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection->skip(1) as $row) {
            $this->data[] = [
                'name' => $row[0], // Primera columna
                'email' => $row[1], // Segunda columna
                'password' => bcrypt($row[2]), // Tercera columna
            ];
        }
    }

    /**
     * Retorna los datos procesados.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
