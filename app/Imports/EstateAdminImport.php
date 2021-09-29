<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EstateAdminImport implements ToCollection, WithHeadingRow, ShouldQueue, WithChunkReading
{

    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {

            User::create([
                'email' => $row['email']
            ])->each(function ($user) use ($row) {
                $user->profile()->create([
                    'firstname' => $row['first_name'],
                    'lastname' => $row['last_name'],
                    'phone_number' => $row['phone_number'],
                    'gender' => $row['gender']
                ]);

                auth()->user->estate_admin->attach();
            });
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
