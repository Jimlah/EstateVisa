<?php

namespace App\Imports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\ToModel;

class AdminImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Admin([
            //
        ]);
    }
}
