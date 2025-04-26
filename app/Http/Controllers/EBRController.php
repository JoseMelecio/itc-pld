<?php

namespace App\Http\Controllers;

use App\Exports\EBRTemplate;
use App\Models\EBR;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Exception;
use Str;

class EBRController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ebrs = EBR::all();

        return Inertia::render('ebr/Index', [
            'ebrs' => $ebrs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);
        $file = $request->file('file');

        $newEbr = EBR::create([
            'id' => Str::uuid(),
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->user()->id,
            'file_name' => $file->getClientOriginalName(),
        ]);

        $newFileName = $newEbr->id . '.' . $file->getClientOriginalExtension();

        $file->storeAs('ebr_files', $newFileName, 'local');

        return redirect()->route('ebr.index');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new EBRTemplate(), 'Plantilla EBR.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EBR $eBR)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EBR $eBR)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EBR $eBR)
    {
        //
    }
}
