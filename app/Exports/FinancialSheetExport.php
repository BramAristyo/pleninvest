<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialSheetExport implements FromCollection, WithTitle, WithHeadings
{
    private $collection;
    private $title;
    private $headings;

    public function __construct($collection, $title, $headings)
    {
        $this->collection = $collection;
        $this->title = $title;
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
