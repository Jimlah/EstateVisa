<?php

namespace App\Imports;

use App\Models\Estate;
use Maatwebsite\Excel\Concerns\ToModel;

class EstateImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Estate([
            'name' => $row[0],
            'code' => $row[1],
        ]);
    }
}
