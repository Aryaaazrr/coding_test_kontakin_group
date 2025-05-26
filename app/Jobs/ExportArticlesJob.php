<?php

namespace App\Jobs;

use App\Exports\ArticlesExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExportArticlesJob implements ShouldQueue
{
   use Queueable;

    protected $fields;
    protected $user;

    public function __construct(array $fields, $user)
    {
        $this->fields = $fields;
        $this->user = $user;
    }

    public function handle(): void
    {
        $filename = 'exports/articles_export_' . now()->timestamp . '.xlsx';

        Excel::store(new ArticlesExport($this->fields), $filename, 'public');

        // Simpan lokasi file ke sesi atau kirim notifikasi ke user
        // Anda juga bisa mengirim email dengan tautan download
    }
}
