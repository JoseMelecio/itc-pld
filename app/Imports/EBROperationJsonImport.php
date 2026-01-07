<?php

namespace App\Imports;

use App\Models\EBR;
use App\Models\EBROperation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class EBROperationJsonImport implements ShouldQueue
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

        foreach ($items as $row) {
            $dataToInsert = [
                'id' => uniqid('', true),
                'ebr_id' => $this->ebrId,
            ];

            // Cada $row es un objeto del JSON
            $dataToInsert = array_merge($dataToInsert, $row);

            $bulkInsert[] = $dataToInsert;

            if (count($bulkInsert) === $chunkSize) {
                EBROperation::insert($bulkInsert);
                $bulkInsert = [];
            }
        }

        if (!empty($bulkInsert)) {
            EBROperation::insert($bulkInsert);
        }

        $ebr = EBR::find($this->ebrId);
        $ebr->import_operations_done = true;
        $ebr->save();

        Log::info("✅ Import JSON de operaciones completado para EBR {$this->ebrId}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("❌ Falló el import JSON de operaciones ({$this->ebrId}): {$exception->getMessage()}");
    }
}
