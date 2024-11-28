<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeNoticeRequest;
use App\Imports\RealEstateLeasingImport;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PLDNoticeController extends Controller
{
    public function showForm(string $noticeType): \Inertia\Response
    {
        $noticeType = Permission::where('name', $noticeType)->first();
        return Inertia::render('pld-notice/ShowForm', ['noticeType' => $noticeType->menu_label]);
    }

    public function makeNotice(MakeNoticeRequest $request): void
    {
        $import = new RealEstateLeasingImport();
        Excel::import($import, $request->file('file'));
        $data = $import->getData();
        Log::info($data);
    }
}
