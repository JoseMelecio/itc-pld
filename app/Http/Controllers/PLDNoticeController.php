<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeNoticeRequest;
use App\Models\PLDNotice;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\ExportXML\RealEstateLeasingExportXML;

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

    public function makeNotice(MakeNoticeRequest $request)
    {
        $dataRequest = $request->validated();
        $dataRequest['tax_id'] = \Auth::user()->tax_id;
        $pldNotice = PLDNotice::findOrFail($dataRequest['pld_notice_id']);
        $importName = str_replace(' ', '', ucwords($pldNotice->name));
        $importClass = "\App\Imports\\" . $importName . "Import";
        $import = new $importClass();

        Excel::import($import, $request->file('file'));
        $data = $import->getData();

        $makeXml = new RealEstateLeasingExportXML(
            data: $data,
            headers: $dataRequest
        );
        $xmlContent = $makeXml->makeXML();

        $fileName = $pldNotice->spanish_name . ' - ' . now()->format('YmdHis') . ".xml";
        Storage::put($fileName, $xmlContent);

        return Response::download(Storage::path($fileName), $fileName)->deleteFileAfterSend(true);

    }
}
