<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Imports\RealEstateAdministrationMassiveImport;
use App\Models\PLDNoticeMassive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProcessPLDNoticeMassive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $noticeMassiveId;

    public function __construct(string $id)
    {
        $this->noticeMassiveId = $id;
    }

    public function handle(): void
    {
        $noticeMassive = PLDNoticeMassive::find($this->noticeMassiveId);
        $filePath = Storage::disk('local')->path('pld_massive/'.$noticeMassive->file_uploaded);

        Excel::import(new RealEstateAdministrationMassiveImport($noticeMassive->id), $filePath);


//        $xmlExport = new RealEstateAdministrationMassiveExport($this->notice->id);
//        $xmlPath = 'pld_massive/' . $this->notice->id . '_result.xml';
//
//        Storage::disk('local')->put($xmlPath, $xmlExport->generate());

//        // 3️⃣ Actualizar estado
//        $this->notice->update([
//            'status' => 'completed',
//            'result_path' => $xmlPath,
//        ]);
    }
}
