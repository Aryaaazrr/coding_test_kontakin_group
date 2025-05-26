<?php

namespace App\Jobs;

use App\Imports\ArticlesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ImportArticlesJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fields;
    protected $filePath;

    public function __construct(array $fields, $filePath)
    {
        $this->fields = $fields;
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $filePath = storage_path("app/{$this->filePath}");

    Excel::import(new ArticlesImport($this->fields), $filePath);

    Storage::delete($this->filePath);
    }
}
