<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeNoticeRequest;
use App\Models\PLDNotice;
use App\Models\SystemLog;
use DOMDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class PLDNoticeController extends Controller
{
    public function showForm(string $notice): \Inertia\Response
    {
        $tenantId = Auth::user()->tenant_id;
        $pldNotice = PLDNotice::where('route_param', $notice)->where('tenant_id', $tenantId)->firstOrFail();
        $customFields = $pldNotice->customFields;

        $xsdName = Str::camel($pldNotice->name);
        $xsd = public_path('xsd/'.$xsdName.'.xsd');

        return Inertia::render('pld-notice/ShowForm', [
            'pldNotice' => $pldNotice,
            'customFields' => $customFields,
            'have_xsd' => file_exists($xsd),
            'multi_subject' => Auth::user()->multi_subject,
        ]);
    }

    public function downloadTemplate(string $notice): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $tenantId = \Auth::user()->tenant_id;
        $pldNotice = PLDNotice::where('route_param', $notice)->where('tenant_id', $tenantId)->firstOrFail();
        $filePath = public_path('templates/'.$pldNotice->template);

        return response()->download($filePath);
    }

    public function makeNotice(MakeNoticeRequest $request): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
    {
        $tenantId = Auth::user()->tenant_id;
        $dataRequest = $request->validated();
        $dataRequest['tax_id'] = Auth::user()->tax_id;
        $pldNotice = PLDNotice::where('id', $dataRequest['pld_notice_id'])->where('tenant_id', $tenantId)->firstOrFail();

        //overwriting tax_id with custom_obligated_subjet
        if (Auth::user()->multi_subject) {
            $dataRequest['tax_id'] = $dataRequest['custom_obligated_subject'];
        }

        $logContent['tenant_id'] = $tenantId;
        $logContent['model_type'] = get_class($pldNotice);
        $logContent['model_id'] = $pldNotice->id;
        $logContent['user_id'] = Auth::user()->id;
        $logContent['type'] = 'create';
        $logContent['content']['status'] = 'pending';
        $systemLog = SystemLog::create($logContent);

        try {
            $importName = str_replace(' ', '', ucwords($pldNotice->name));
            $importClass = "\App\Imports\\" . $importName . 'Import';
            $import = new $importClass;

            $exportClass = "\App\Exports\\" . $importName . 'ExportXML';

            $logContent['file_import_name'] = $request->file('file');
            Excel::import($import, $request->file('file'));
            $data = $import->getData();

            $makeXml = new $exportClass(
                data: $data,
                headers: $dataRequest
            );
            $xmlContent = $makeXml->makeXML();

            $xsdName = Str::camel($pldNotice->name);
            $xsd = public_path('xsd/' . $xsdName . '.xsd');;

            //file_exists($xsd)
            if (file_exists($xsd)) {
                $dom = new DOMDocument;
                $dom->loadXML($xmlContent);
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
                    return response()->json(['errors' => $errorMessages], 422);
                }
            }

            $specialCharacters = [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                'ñ' => 'n', 'Ñ' => 'N', 'ü' => 'u', 'Ü' => 'U',
            ];
            $noticeName = strtr($pldNotice->spanish_name, $specialCharacters);

            $fileName = $noticeName . ' - ' . now()->format('YmdHis') . '.xml';
            Storage::put($fileName, $xmlContent);

            $content = $systemLog->content ?? [];
            $content['status'] = 'success';
            $content['file_name'] = $fileName;
            $systemLog->content = $content;
            $systemLog->save();

            return Response::download(Storage::path($fileName), $fileName)->deleteFileAfterSend(true);
        } catch (Throwable $e) {
            Log::error($e);
            $errorString = sprintf(
                "%s: %s in %s:%d",
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );

            $content = $systemLog->content ?? [];
            $content['status'] = 'error';
            $content['messages'] = $errorString;
            $systemLog->content = $content;
            $systemLog->save();


            return response()->json([
                'errors'   => ['Ocurrió un error inesperado en el servidor'],
            ], 500);
        }
    }
}
