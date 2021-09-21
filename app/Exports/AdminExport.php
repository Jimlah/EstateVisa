<?php

namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdminExport implements FromCollection, WithHeadings
{

    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Admin::with('roles')->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Gender',
            'Created At'
        ];
    }
}
