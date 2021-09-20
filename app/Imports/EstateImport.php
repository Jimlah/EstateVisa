<?php

namespace App\Imports;

use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstateImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        dd($row);
        // foreach ($rows as $row) {
        // //    $request = new Request();
        // //     $request->replace($ row);
        //     $estate = new Estate();
        //     $estate->name = $row['Estate Name'];
        //     $estate->code = $row['Estate code'];
        //     $estate->save();
        // }

        return new Estate(
            [
                'name' => $row['Estate Name'],
                'code' => $row['Estate code'],
            ]
        );
    }
}
