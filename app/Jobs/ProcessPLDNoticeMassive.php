<?php

namespace App\Jobs;

use App\Exports\RealEstateAdministrationMassiveExport;
use App\Imports\RealEstateAdministrationMassiveImport;
use App\Models\PLDNotice;
use App\Models\PLDNoticeMassive;
use App\Models\SystemLog;
use DOMDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Throwable;

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
        $pldNotice = PLDNotice::find($noticeMassive->pld_notice_id);
        $formData = $noticeMassive->form_data;
        $filePath = Storage::disk('local')->path('pld_massive/'.$noticeMassive->file_uploaded);

        $logContent = [];
        $logContent['model_type'] = get_class($pldNotice);
        $logContent['model_id'] = $pldNotice->id;
        $logContent['user_id'] = $noticeMassive->user_id;
        $logContent['type'] = 'create';
        $logContent['content']['status'] = 'pending';
        $systemLog = SystemLog::create($logContent);

        try {
            Excel::import(new RealEstateAdministrationMassiveImport($noticeMassive->id), $filePath);

            $xmlExport = new RealEstateAdministrationMassiveExport($noticeMassive->id);
            $xmlGenerated = $xmlExport->toXml();
            $timestamp = Carbon::now()->format('Y_m_d_His');
            $fileName = 'administracion_inmuebles_massivo_' . $timestamp . '.xml';
            $xmlPath = 'pld_massive/' . $fileName;

            $xsdName = Str::camel($pldNotice->name);
            $xsd = public_path('xsd/' . $xsdName . '.xsd');
            Storage::disk('public')->put($xmlPath, $xmlGenerated);

            $status = 'done';
            $errorMessages = null;
            //file_exists($xsd)
            if (file_exists($xsd)) {
                $dom = new DOMDocument;
                $dom->loadXML($xmlGenerated);
                libxml_use_internal_errors(true);
                if (!$dom->schemaValidate($xsd)) {
                    $errors = libxml_get_errors();
                    $errorMessages = array_map(fn($error) => $error->message, $errors);

                    libxml_clear_errors();
                    $content = $systemLog->content ?? [];
                    $content['status'] = 'error';
                    $content['messages'] = $errorMessages;

                    $systemLog->content = $content;
                    $systemLog->save();
                    $status = 'error';
                }
            }

            $noticeMassive->update([
                'status' => $status,
                'errors' => $errorMessages,
                'xml_generated' => $xmlPath,
            ]);

            $content = $systemLog->content ?? [];
            $content['status'] = 'success';
            $content['messages'] = "file_name: " . $fileName;

        } catch (Throwable $th) {
            Log::error('Error procesando PLDNoticeMassive: ' . $th->getMessage(), [
                'notice_massive_id' => $this->noticeMassiveId,
                'trace' => $th->getTraceAsString(),
            ]);

            $noticeMassive->update([
                'status' => 'error',
                'errors' => $th->getMessage(),
            ]);

            $content = $systemLog->content ?? [];
            $content['status'] = 'error';
            $content['messages'] = $th->getMessage();
        }
    }
}
