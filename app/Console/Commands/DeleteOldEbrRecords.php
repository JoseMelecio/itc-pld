<?php

namespace App\Console\Commands;

use App\Models\EBR;
use App\Models\EBRClients;
use App\Models\EBROperation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteOldEbrRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebr:delete-old-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old ebr records';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Delete old ebr records');
        $limitDate = Carbon::now()->subMinutes(30);

        $ebrs = EBR::where('created_at', '<', $limitDate)->get();
        foreach ($ebrs as $ebr) {
            EBROperation::where('ebr_id', $ebr->id)->delete();
            EBRClients::where('ebr_id', $ebr->id)->delete();
            $ebr->delete();
        }
    }
}
