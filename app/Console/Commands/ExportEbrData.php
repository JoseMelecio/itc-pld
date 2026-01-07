<?php

namespace App\Console\Commands;

use App\Jobs\ExportTableToJson;
use Illuminate\Console\Command;

class ExportEbrData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebr:export-data {ebr_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export ebr_clients and ebr_operations table to JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ebrId = $this->argument('ebr_id');

        // Despachamos un job por tabla
        ExportTableToJson::dispatch(
            table: 'ebr_clients',
            ebrId: $ebrId
        );

        ExportTableToJson::dispatch(
            table: 'ebr_operations',
            ebrId: $ebrId
        );

        $this->info("Jobs de exportación despachados para ebr_id={$ebrId}");
    }
}
