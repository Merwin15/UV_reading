<?php

namespace App\Exports;

use App\Models\SensorReading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SensorReadingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // You can customize which columns you want
        return SensorReading::orderBy('created_at', 'desc')
            ->get(['id', 'uv_reading', 'created_at', 'ip_address']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'UV / Heat Value',
            'Timestamp',
            'IP Address',
        ];
    }
}