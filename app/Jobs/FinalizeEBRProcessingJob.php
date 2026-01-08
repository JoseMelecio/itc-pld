<?php

namespace App\Jobs;

use App\Exports\EBRMultiSheetExport;
use App\Models\EBR;
use App\Models\EBRConfiguration;
use App\Models\EBRRiskElementIndicatorRelated;
use App\Models\EBRRiskElementRelated;
use App\Exports\EBRRiskInherentExport;
use App\Models\EBRRiskElementRelatedAverage;
use App\Services\JsonQueryBuilder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\DB;
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

            $average_risk_inherent_concentration = [];
            $average_risk_level_features = [];
            $average_risk_level_integrated = [];
            $weight_impact_range_header = [];
            $frequency_range_header = [];
            $risk_inherent_concentration_header = [];

            foreach ($result as $item) {

                $newElement = [
                    'ebr_id' => $ebr->id,
                    'ebr_risk_element_id' => $riskElement->id,
                    'element' => $item->label,
                    'amount_mxn' => $item->amount_mxn,
                    'total_clients' => $item->total_clients,
                    'total_operations' => $item->total_operations,
                    'weight_range_impact' => ($item->amount_mxn / $ebr->total_operation_amount) * 100,
                    'frequency_range_impact' => ((($item->total_operations / $ebr->total_operations) + ($item->total_clients / $ebr->total_clients)) / 2) * 100,
                    'risk_level_features' => 0,
                    'risk_level_integrated' => 0,
                ];
                $newElement['risk_inherent_concentration'] = ($newElement['weight_range_impact'] + $newElement['frequency_range_impact']) / 2;

                $average_risk_inherent_concentration[] = $newElement['risk_inherent_concentration'];
                $average_risk_level_features[] = 0;
                $average_risk_level_integrated[] = 0;
                $weight_impact_range_header[] = $item->amount_mxn;
                $frequency_range_header[] = $item->total_operations;

                EBRRiskElementRelated::create($newElement);
            }

            $relatedAverage = EBRRiskElementRelatedAverage::create([
                'ebr_id' => $ebr->id,
                'ebr_risk_element_id' => $riskElement->id,
                'average_risk_inherent_concentration' => collect($average_risk_inherent_concentration)->avg(),
                'average_risk_level_features' => collect($average_risk_level_features)->avg(),
                'average_risk_level_integrated' => collect($average_risk_level_integrated)->avg(),
                'weight_impact_range_header' => collect($weight_impact_range_header)->sum() / $ebr->total_operation_amount,
                'frequency_range_header' => collect($frequency_range_header)->sum() / $ebr->total_operations,
            ]);

            $relatedAverage['risk_inherent_concentration_header'] = ($relatedAverage['weight_impact_range_header'] + $relatedAverage['frequency_range_header']) / 2;
        }

        foreach ($ebrConfiguration->riskIndicators as $riskIndicator) {
            if (empty($riskIndicator->sql) || !$riskIndicator->active) continue;
            [$sql, $bindings] = $this->normalizeNamedParameter($riskIndicator->sql, 'ebr_id', $ebr->id);
            $result = DB::connection('mysql_readonly')->select($sql, $bindings);
            $newIndicatorRelated = [
                'ebr_id' => $ebr->id,
                'ebr_risk_element_id' => $riskIndicator->risk_element_id,
                'characteristic' => $riskIndicator->characteristic,
                'key' => $riskIndicator->key,
                'name' => $riskIndicator->name,
                'description' => $riskIndicator->description,
                'risk_indicator' => $riskIndicator->risk_indicator,
                'order' => $riskIndicator->order,
                'amount' => $result[0]->amount_mxn,
                'related_clients' => $result[0]->total_clients,
                'related_operations' => $result[0]->total_operations,
                'weight_range_impact' => 0,
                'frequency_range_impact' => 0,
                'characteristic_concentration' => 0,
            ];
            EBRRiskElementIndicatorRelated::create($newIndicatorRelated);


        }

        $path = "ebr_reports/reporte_ebr_{$ebr->id}.xlsx";
        Excel::store(new EBRMultiSheetExport($ebr), $path, 'public');
        $ebr->status = 'done';
        $ebr->saveQuietly();
    }

    public function normalizeNamedParameter(string $sql, string $paramName, $value): array
    {
        $counter = 0;
        $bindings = [];

        $normalizedSql = preg_replace_callback(
            '/:' . preg_quote($paramName, '/') . '\b/',
            function () use (&$counter, &$bindings, $paramName, $value) {
                $counter++;
                $newParam = "{$paramName}_{$counter}";
                $bindings[$newParam] = $value;
                return ':' . $newParam;
            },
            $sql
        );

        return [$normalizedSql, $bindings];
    }
}
