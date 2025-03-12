<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeNoticeRequest;
use App\Models\PLDNotice;
use App\Services\SystemLogService;
use DOMDocument;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PLDNoticeController extends Controller
{
    public function showForm(string $notice): \Inertia\Response
    {
        $pldNotice = PLDNotice::where('route_param', $notice)->firstOrFail();
        $customFields = $pldNotice->customFields;

        $xsdName = Str::camel($pldNotice->name);
        $xsd = public_path('xsd/'.$xsdName.'.xsd');

        return Inertia::render('pld-notice/ShowForm', [
            'pldNotice' => $pldNotice,
            'customFields' => $customFields,
            'have_xsd' => file_exists($xsd),
        ]);
    }

    public function downloadTemplate(string $notice): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $pldNotice = PLDNotice::where('route_param', $notice)->firstOrFail();
        $filePath = public_path('templates/'.$pldNotice->template);

        return response()->download($filePath);
    }

    public function makeNotice(MakeNoticeRequest $request): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
    {
        $dataRequest = $request->validated();
        $dataRequest['tax_id'] = \Auth::user()->tax_id;
        $pldNotice = PLDNotice::findOrFail($dataRequest['pld_notice_id']);
        $logContent['type'] = 'create';
        $logContent['model_type'] = get_class($pldNotice);
        $logContent['model_id'] = $pldNotice->id;

        $importName = str_replace(' ', '', ucwords($pldNotice->name));
        $importClass = "\App\Imports\\".$importName.'Import';
        $import = new $importClass;

        $exportClass = "\App\Exports\\".$importName.'ExportXML';

        $logContent['file_import_name'] = $request->file('file');
        Excel::import($import, $request->file('file'));
        $data = $import->getData();

        $makeXml = new $exportClass(
            data: $data,
            headers: $dataRequest
        );
        $xmlContent = $makeXml->makeXML();

        $xsdName = Str::camel($pldNotice->name);
        $xsd = public_path('xsd/'.$xsdName.'.xsd');

        if (file_exists($xsd)) {
            $dom = new DOMDocument;
            $dom->loadXML($xmlContent);
            libxml_use_internal_errors(true);
            if (! $dom->schemaValidate($xsd)) {
                $errors = libxml_get_errors();
                $errorMessages = array_map(fn ($error) => $error->message, $errors);

                libxml_clear_errors();
                $logContent['content']['status'] = 'error';
                $logContent['content']['messages'] = $errorMessages;
                SystemLogService::add($logContent);
                return response()->json(['errors' => $errorMessages], 422);
            }
        }

        $specialCharacters = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N', 'ü' => 'u', 'Ü' => 'U',
        ];
        $noticeName = strtr($pldNotice->spanish_name, $specialCharacters);

        $fileName = $noticeName.' - '.now()->format('YmdHis').'.xml';
        Storage::put($fileName, $xmlContent);

        $logContent['content']['status'] = 'success';
        $logContent['content']['file_name'] = $fileName;
        SystemLogService::add($logContent);

        return Response::download(Storage::path($fileName), $fileName)->deleteFileAfterSend(true);
    }
}
