<?php

namespace App\Imports;

use App\Models\Entry;
use Maatwebsite\Excel\Concerns\ToModel;

class EntriesImport implements ToModel
{
    public function model(array $row)
    {
        return new Entry([
            'name'     => $row[0],
            'is_private' => $row[1],
            'download_count' => $row[2],
            'whatsapp_count' => $row[3],
         ]);
    }
}