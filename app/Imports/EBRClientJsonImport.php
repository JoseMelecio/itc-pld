<?php

namespace App\Imports;

use App\Models\EBR;
use App\Models\EBRClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class EBRClientJsonImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;
    protected string $ebrId;
    protected string $userId;

    public function __construct(string $path, string $ebrId, string $userId)
    {
        $this->path = $path;
        $this->ebrId = $ebrId;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $fullPath = Storage::path($this->path);

        $items = Items::fromFile(
            $fullPath,
            ['decoder' => new ExtJsonDecoder(true)]
        );

        $bulkInsert = [];
        $chunkSize = 500;

        foreach ($items as $item) {
            $data = [
                'id' => uniqid('', true),
                'ebr_id' => $this->ebrId,
            ];

            $data = array_merge($data, $item);

            $bulkInsert[] = $data;

            if (count($bulkInsert) === $chunkSize) {
                EBRClient::insert($bulkInsert);
                $bulkInsert = [];
            }
        }

        if (!empty($bulkInsert)) {
            EBRClient::insert($bulkInsert);
        }

        $ebr = EBR::find($this->ebrId);
        $ebr->import_clients_done = true;
        $ebr->save();

        Log::info("✅ Import JSON de clientes completado para EBR {$this->ebrId}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("❌ Falló el import JSON de clientes ({$this->ebrId}): {$exception->getMessage()}");
    }
}
