<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminImport implements ToCollection, WithHeadingRow, ShouldQueue
{

    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            User::create([
                'email' => $row['email']
            ])->each(function ($user) use ($row) {
                $user->profile()->create([
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'phone_number' => $row['phone_number'],
                    'gender' => $row['gender']
                ]);

                $user->admin()->create([]);
            });
        }
    }
}
