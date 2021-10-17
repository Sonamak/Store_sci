<?php

namespace App\Exports;

use App\Models\Entry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class EntriesExport implements FromCollection, WithColumnWidths
{
    use Exportable;

    public function collection()
    {
        return Entry::all();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10, 
            'B' => 55,           
        ];
    }
}