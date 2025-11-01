<?php

namespace App\Imports;

use App\Models\EBR;
use App\Models\EBRClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Events\AfterImport;


class EBRClientImport implements ToCollection, ShouldQueue, WithChunkReading, WithStartRow, WithHeadingRow, WithEvents
{
    use Queueable;

    protected string $ebrId;
    protected string $userId;

    public function __construct(string $ebrId, string $userId)
    {
        $this->ebrId = $ebrId;
        $this->userId = $userId;
    }

    public function collection(Collection $collection): void
    {
        $bulkInsert = [];

        foreach ($collection as $row) {
            $dataToInsert = ['id' => uniqid('', true)];
            $dataToInsert['ebr_id'] = $this->ebrId;

            $dataToInsert = array_merge($dataToInsert, $row->toArray());

            $bulkInsert[] = $dataToInsert;
        }

        if (!empty($bulkInsert)) {
            EBRClient::insert($bulkInsert);
        }
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                Log::info("✅ Import de clientes completado para EBR {$this->ebrId}");
                $ebr = EBR::find($this->ebrId);
                $ebr->import_clients_done = true;
                $ebr->save();
            },

            ImportFailed::class => function (ImportFailed $event) {
                Log::error("❌ Falló el import de clientes ({$this->ebrId}): " . $event->getException()->getMessage());
            },
        ];
    }
}
