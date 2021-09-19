<?php

namespace App\Exports;

use App\Models\Estate;
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
        return Estate::all();
    }

    public function headings(): array
    {
        return [
            'Estate ID',
            'Owner First Name',
            'Owner Last Name',
            'Owner Email',
            'Owner Phone Number',
            'Owner Gender',
            'Estate Name',
            'Estate Address',
            'Estate Code',
            'Estate Logo',
            'Created At',
        ];
    }

    public function map($estate): array
    {
        return [
            'Estate ID' => $estate->id,
            'Owner First Name' => $estate->user->profile->firstname,
            'Owner Last Name' => $estate->user->profile->lastname,
            'Owner Email' => $estate->user->email,
            'Owner Phone Number' => $estate->user->profile->phone_number,
            'Owner Gender' => $estate->user->profile->gender,
            'Estate Name' => $estate->name,
            'Estate Address' => $estate->address,
            'Estate Code' => $estate->code,
            'Estate Logo' => $estate->logo,
            'Created At' => $estate->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
