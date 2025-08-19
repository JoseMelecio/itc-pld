<?php

namespace App\Imports;

use App\Jobs\FinalizeEBRProcessingJob;
use App\Models\EBRConfiguration;
use App\Models\EBROperation;
use App\Models\EBRTemplateComposition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class EBROperationImport implements ToCollection, ShouldQueue, WithChunkReading, WithStartRow//, WithEvents
{
    use Queueable;

    protected string $ebrId;
    protected string $tenantId;
    protected string $userId;

    public function __construct(string $ebrId, string $tenantId, string $userId)
    {
        $this->ebrId = $ebrId;
        $this->tenantId = $tenantId;
        $this->userId = $userId;
    }

    public function collection(Collection $collection): void
    {
        $ebrConfiguration = EBRConfiguration::where('tenant_id', $this->tenantId)
            ->where('user_id', $this->userId)->first();

        $column_var_name = $ebrConfiguration->template_operations_config;

        $bulkInsert = [];

        foreach ($collection as $row) {
            $dataToInsert = [];
            foreach ($column_var_name as $key => $var_name) {
                $dataToInsert[$var_name] = $row[$key] ?? null;
            }
            $dataToInsert['ebr_id'] = $this->ebrId;
            $bulkInsert[] = $dataToInsert;
        }

        if (!empty($bulkInsert)) {
            EBROperation::insert($bulkInsert);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 2;
    }
}
