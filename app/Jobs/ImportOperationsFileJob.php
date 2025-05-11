<?php

namespace App\Jobs;

use App\Imports\EBROperationImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ImportOperationsFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $ebrId, public string $filePath)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new EBROperationImport($this->ebrId), $this->filePath);
    }
}
