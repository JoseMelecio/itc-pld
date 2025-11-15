<?php

namespace App\Imports;

use App\Exports\BlockedPersonMassiveResultExport;
use App\Models\BlockedPersonMassive;
use App\Models\BlockedPersonMassiveDetail;
use App\Services\PersonBlockedFinderService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class BlockedPersonMassiveSearchImport implements ToCollection, ShouldQueue, WithChunkReading, WithStartRow, WithEvents
{
    use Queueable;
    protected string $massiveSearchId;

    public function __construct(string $massiveSearchId)
    {
        $this->massiveSearchId = $massiveSearchId;
    }

    public function collection(Collection $collection): void
    {
        $bulkInsert = [];

        foreach ($collection as $row) {
            $dataToInsert = ['id' => uniqid('', true)];
            $dataToInsert['blocked_person_massive_id'] = $this->massiveSearchId;
            $dataToInsert['name'] = $row[0];
            $dataToInsert['alias'] = $row[1];
            $dataToInsert['date'] = $row[2];

            $findService = new PersonBlockedFinderService;
            $finderResult = $findService->finder([[
                'name' => $row[0],
                'alias' => $row[1],
                'date' => $row[2],
            ]]);
            $dataToInsert['coincidence'] = $finderResult[0]['result'];

            $bulkInsert[] = $dataToInsert;
        }

        if (!empty($bulkInsert)) {
            BlockedPersonMassiveDetail::insert($bulkInsert);
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

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {

                $fileName = "massive_search/busqueda_masiva_" . Carbon::now()->format('His') . ".xlsx";

                Excel::store(
                    new BlockedPersonMassiveResultExport($this->massiveSearchId),
                    $fileName,
                    'public'
                );

                $totalPersons = BlockedPersonMassiveDetail::where('blocked_person_massive_id', $this->massiveSearchId)->count();
                $matches = BlockedPersonMassiveDetail::where('blocked_person_massive_id', $this->massiveSearchId)->where('coincidence', '!=', 'Sin coincidencias')->count();
                BlockedPersonMassive::where('id', $this->massiveSearchId)
                    ->update([
                        'status' => 'done',
                        'download_file_name' => $fileName,
                        'total_rows' => $totalPersons,
                        'matches' => $matches,
                    ]);

            }
        ];
    }
}
