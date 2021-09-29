<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\User;
use App\Models\Estate;
use App\Models\EstateAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstateImport implements ToCollection, WithHeadingRow, ShouldQueue, WithChunkReading
{
    use Importable;

     /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            User::create([
                'email' => $value['email'],
            ])->each(function ($user) use ($value) {
                $user->profile()->create([
                    'firstname' => $value['first_name'],
                    'lastname' => $value['last_name'],
                    'phone_number' => $value['phone_number'],
                    'gender' => $value['gender']
                ]);

                Estate::create([
                    'name' => $value['name'],
                    'code' => $value['code'],
                    'address' => $value['address'],
                    'logo' => $value['logo'],
                ])->admin()->save(new EstateAdmin([
                    'user_id' => $user->id,
                    'role' => User::ESTATE_SUPER_ADMIN,
                ]));
            });
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
