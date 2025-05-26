<?php

namespace App\Imports;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class ArticlesImport implements ToModel
{
   protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function model(array $row)
    {  $data = [];
    foreach ($this->fields as $field) {
        if (isset($row[$field])) {
            $data[$field] = $row[$field];
        }
    }

    Log::info('Importing row', $data);

    if (empty($data)) {
        return null;
    }

    return new Article($data);

    }
}
