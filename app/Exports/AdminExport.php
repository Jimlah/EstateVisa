<?php

namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class AdminExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Admin::with(['user', 'user.profile'])->get();
    }

    public function headings(): array
    {
        return [
            'Id',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Gender',
            'Created At'
        ];
    }

    public function map($admin): array
    {
        return [
            "Id" => $admin->id,
            "First Name" => $admin->user->profile->firstname,
            "Last Name" => $admin->user->profile->lastname,
            "Email" => $admin->user->email,
            "Phone Number" => $admin->user->profile->phone_number,
            "Gender" => $admin->user->profile->gender,
            "Created_at" => $admin->created_at->format('d/m/Y H:i:s')
        ];
    }
}
