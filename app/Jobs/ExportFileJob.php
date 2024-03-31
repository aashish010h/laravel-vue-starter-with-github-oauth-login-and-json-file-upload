<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FileExport;

class ExportFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $fileExt;
    protected $exportFormat;
    protected $fileName;
    protected $fileId;

    /**
     * Create a new job instance.
     */
    public function __construct($fileId)
    {
        $this->fileId = $fileId;
        $this->fileExt = 'xlsx';
        $this->exportFormat = \Maatwebsite\Excel\Excel::XLSX;
        $this->fileName = "file-" . date('d-m-Y') . "." . $this->fileExt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::download(new FileExport($this->fileId), $this->fileName, $this->exportFormat);
    }
}
