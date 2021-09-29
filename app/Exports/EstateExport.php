<?php

namespace App\Exports;

use App\Models\Estate;
use App\Models\EstateAdmin;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class EstateExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return EstateAdmin::owner()->orderBy('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Gender',
            'Name',
            'Address',
            'Code',
            'Logo',
            'Role',
            'Created At',
        ];
    }

    public function map($estate): array
    {
        // dd($estate->toArray());
        return [
            'Id' => $estate->id,
            'First Name' => $estate->user->profile?->firstname,
            'Last Name' => $estate->user->profile?->lastname,
            'Email' => $estate->user->email,
            'Phone Number' => $estate->user->profile?->phone_number,
            'Gender' => $estate->user->profile?->gender,
            'Name' => $estate->estate->name,
            'Address' => $estate->estate->address,
            'Code' => $estate->estate->code,
            'Logo' => $estate->estate->logo,
            'Role' => $estate->role,
            'Created At' => $estate->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
