<?php

namespace App\Jobs;

use App\Models\EBR;
use App\Models\EBRRiskElementRelated;
use App\Exports\EBRExport;
use Illuminate\Foundation\Events\Dispatchable;
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

        foreach ($ebr->type->riskElements as $riskElement) {
            $dataCalculated = $riskElement->calculate($ebr->id);
            foreach ($dataCalculated as $value) {
                EBRRiskElementRelated::create([
                    'ebr_id' => $ebr->id,
                    'ebr_risk_element_id' => $riskElement->id,
                    'element' => $value['risk_element'],
                    'amount_mxn' => $value['amount_mxn'],
                    'total_clients' => $value['total_clients'],
                    'total_operations' => $value['total_operations'],
                    'weight_range_impact' => ($value['amount_mxn'] / $ebr->total_operation_amount) * 100,
                    'frequency_range_impact' => 0,
                    'risk_inherent_concentration' => 0,
                    'risk_level_features' => 0,
                    'risk_level_integrated' => 0,
                ]);
            }
        }

        $ebr->status = 'done';
        $ebr->save();

        $path = "ebr_reports/reporte_ebr_{$ebr->id}.xlsx";
        Excel::store(new EBRExport($ebr), $path, 'public');
    }
}
