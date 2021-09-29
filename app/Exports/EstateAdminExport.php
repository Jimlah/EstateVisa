<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\EstateAdmin;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EstateAdminExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return EstateAdmin::estateOnly()->get();
    }

    public function headings(): array
    {
        return array_merge(
            [
                'Estate'
            ],
            (new AdminExport)->headings(),
        );
    }

    public function map($admin): array
    {
        return array_merge(
            [
                'Estate' => $admin->estate->name
            ],
            (new AdminExport)->map($admin),
        );
    }
}
