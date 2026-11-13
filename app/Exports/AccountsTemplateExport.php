<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsTemplateExport implements FromArray, WithColumnWidths, WithHeadings
{
    public function headings(): array
    {
        return ['fullname', 'username', 'password', 'role_id', 'phone', '', ''];
    }

    public function array(): array
    {
        return [
            ['', '', '', '', '', '', 'Id Role'],
            ['', '', '', '', '', '', 'Pic = 2'],
            ['', '', '', '', '', '', 'Guru = 3'],
            ['', '', '', '', '', '', 'Organisasi = 4'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 24,
            'C' => 22,
            'D' => 12,
            'E' => 20,
            'F' => 4,
            'G' => 20,
        ];
    }
}
