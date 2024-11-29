<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeNoticeRequest;
use App\Imports\RealEstateLeasingImport;
use App\Models\Permission;
use App\Models\PLDNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PLDNoticeController extends Controller
{
    public function showForm(string $notice): \Inertia\Response
    {
        Log::info($notice);
        $pldNotice = PLDNotice::where('route_param', $notice)->firstOrFail();
        return Inertia::render('pld-notice/ShowForm', ['pldNotice' => $pldNotice]);
    }

    public function makeNotice(MakeNoticeRequest $request): void
    {
        $import = new RealEstateLeasingImport();
        Excel::import($import, $request->file('file'));
        $data = $import->getData();
        Log::info($data);
    }
}
