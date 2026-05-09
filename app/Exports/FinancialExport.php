<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinancialExport implements WithMultipleSheets
{
    private $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
