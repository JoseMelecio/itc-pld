<?php

namespace App\Jobs;

use App\Imports\EBROperationImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Batchable;

class ImportOperationsFileJob implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $ebrId, public string $path)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new EBROperationImport($this->ebrId), $this->path);
    }
}
