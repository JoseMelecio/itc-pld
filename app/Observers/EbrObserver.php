<?php

namespace App\Observers;

use App\Jobs\FinalizeEBRProcessingJob;
use App\Models\EBR;
use Illuminate\Support\Facades\Log;

class EbrObserver
{
    /**
     * Handle the EBR "created" event.
     */
    public function created(EBR $ebr): void
    {
        //
    }

    /**
     * Handle the EBR "updated" event.
     */
    public function updated(EBR $ebr): void
    {
        Log::info("Observer updated");
        if ($ebr->import_clients_done && $ebr->import_operations_done) {

            if (!$ebr->status || $ebr->status !== 'done') {

                Log::info("✅ Ambos imports completados para EBR {$ebr->id}. Despachando job...");

                // Lanzamos el job final
                dispatch(new FinalizeEBRProcessingJob($ebr->id));

            }
        }
    }

    /**
     * Handle the EBR "deleted" event.
     */
    public function deleted(EBR $eBR): void
    {
        //
    }

    /**
     * Handle the EBR "restored" event.
     */
    public function restored(EBR $eBR): void
    {
        //
    }

    /**
     * Handle the EBR "force deleted" event.
     */
    public function forceDeleted(EBR $eBR): void
    {
        //
    }
}
