<?php

namespace App\Jobs;

use App\Exports\EBRMultiSheetExport;
use App\Models\EBR;
use App\Models\EBRConfiguration;
use App\Models\EBRRiskElementRelated;
use App\Exports\EBRRiskInherentExport;
use App\Services\JsonQueryBuilder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FinalizeEBRProcessingJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $ebrId;

    public function __construct(int $ebrId)
    {
        $this->ebrId = $ebrId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ebr = EBR::findOrFail($this->ebrId);
        $ebr->total_operation_amount = $ebr->total_amount;
        $ebr->total_clients = $ebr->total_clients_count;
        $ebr->total_operations = $ebr->total_operations_count;
        $ebr->maximum_risk_level = $ebr->maximum_risk_level_count;

        $ebrConfiguration = EBRConfiguration::where('user_id', $ebr->user_id)->first();
        foreach ($ebrConfiguration->riskElements as $riskElement) {
            $builder = new JsonQueryBuilder($riskElement->report_config, $ebr->id);
            $query = $builder->build();
            $result = $query->get();

            foreach ($result as $item) {
                $newElement = [
                    'ebr_id' => $ebr->id,
                    'ebr_risk_element_id' => $riskElement->id,
                    'element' => $item->label,
                    'amount_mxn' => $item->amount_mxn,
                    'total_clients' => $item->total_clients,
                    'total_operations' => $item->total_operations,
                    'weight_range_impact' => ($item->amount_mxn / $ebr->total_operation_amount) * 100,
                    'frequency_range_impact' => 0,
                    'risk_inherent_concentration' => 0,
                    'risk_level_features' => 0,
                    'risk_level_integrated' => 0,
                ];
                EBRRiskElementRelated::create($newElement);
            }

        }

        $path = "ebr_reports/reporte_ebr_{$ebr->id}.xlsx";
        Excel::store(new EBRMultiSheetExport($ebr), $path, 'public');
        $ebr->status = 'done';
        $ebr->saveQuietly();
    }
}
