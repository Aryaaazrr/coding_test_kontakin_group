<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArticlesExport implements FromCollection, WithHeadings
{
     protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function collection()
    {
        return Article::select($this->fields)->get();
    }

    public function headings(): array
    {
        return $this->fields;
    }
}