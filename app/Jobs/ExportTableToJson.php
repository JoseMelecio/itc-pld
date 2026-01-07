<?php

namespace App\Jobs;

use App\Models\EBR;
use App\Models\EBRConfiguration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportTableToJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $table;
    public int $ebrId;

    public int $timeout = 0; // evita timeout en procesos largos

    public function __construct(string $table, int $ebrId)
    {
        $this->table = $table;
        $this->ebrId = $ebrId;
    }

    public function handle()
    {
        $ebr = EBR::find($this->ebrId);
        $ebrConfiguration = EBRConfiguration::where('user_id', $ebr->user_id)->first();

        if ($this->table === 'ebr_clients') {
            $select = $ebrConfiguration->template_clients_config;
        } else {
            $select = $ebrConfiguration->template_operations_config;
        }

        $select = array_map(function ($field) {
            return Str::ascii($field);
        }, $select);


        $fileName = "{$this->table}_ebr_{$this->ebrId}.json";
        $path = "exports/{$fileName}";

        Storage::put($path, '[');

        $first = true;

        DB::table($this->table)
            ->select($select)
            ->where('ebr_id', $this->ebrId)
            ->orderBy('id')
            ->chunk(1000, function ($rows) use (&$first, $path) {
                foreach ($rows as $row) {
                    if (!$first) {
                        Storage::append($path, ',');
                    }

                    Storage::append(
                        $path,
                        json_encode($row, JSON_UNESCAPED_UNICODE)
                    );

                    $first = false;
                }
            });

        // Cerramos el JSON
        Storage::append($path, ']');
    }
}
