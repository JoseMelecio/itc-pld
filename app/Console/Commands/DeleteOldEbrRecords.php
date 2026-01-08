<?php

namespace App\Console\Commands;

use App\Models\EBR;
use App\Models\EBRClient;
use App\Models\EBROperation;
use App\Models\EBRRiskElementIndicatorRelated;
use App\Models\EBRRiskElementRelated;
use App\Models\EBRRiskElementRelatedAverage;
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
            EBRRiskElementRelated::where('ebr_id', $ebr->id)->delete();
            EBRRiskElementRelatedAverage::where('ebr_id', $ebr->id)->delete();
            EBRRiskElementIndicatorRelated::where('ebr_id', $ebr->id)->delete();
            EBROperation::where('ebr_id', $ebr->id)->delete();
            EBRClient::where('ebr_id', $ebr->id)->delete();
            $ebr->delete();
        }
    }
}
