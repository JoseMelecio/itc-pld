<?php

namespace App\Http\Controllers;

use App\Exports\MutualLoanCreditExportXML;
use App\Exports\RealEstateLeasingExportXML;
use App\Http\Requests\MakeNoticeRequest;
use App\Models\PLDNotice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PLDNoticeController extends Controller
{
    public function showForm(string $notice): \Inertia\Response
    {
        $pldNotice = PLDNotice::where('route_param', $notice)->firstOrFail();
        return Inertia::render('pld-notice/ShowForm', ['pldNotice' => $pldNotice]);
    }

    public function downloadTemplate(string $notice): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $pldNotice = PLDNotice::where('route_param', $notice)->firstOrFail();
        $filePath = public_path('templates/' . $pldNotice->template);

        return response()->download($filePath);
    }

    public function makeNotice(MakeNoticeRequest $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $dataRequest = $request->validated();
        $dataRequest['tax_id'] = \Auth::user()->tax_id;
        $pldNotice = PLDNotice::findOrFail($dataRequest['pld_notice_id']);

        $importName = str_replace(' ', '', ucwords($pldNotice->name));
        $importClass = "\App\Imports\\" . $importName . "Import";
        $import = new $importClass();

        $exportClass = "\App\Exports\\" . $importName . "ExportXML";

        Excel::import($import, $request->file('file'));
        $data = $import->getData();

        $makeXml = new $exportClass(
            data: $data,
            headers: $dataRequest
        );
        $xmlContent = $makeXml->makeXML();

        $specialCharacters = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N', 'ü' => 'u', 'Ü' => 'U'
        ];
        $noticeName = strtr($pldNotice->spanish_name, $specialCharacters);

        $fileName = $noticeName . ' - ' . now()->format('YmdHis') . ".xml";
        Storage::put($fileName, $xmlContent);

        return Response::download(Storage::path($fileName), $fileName)->deleteFileAfterSend(true);

    }
}
