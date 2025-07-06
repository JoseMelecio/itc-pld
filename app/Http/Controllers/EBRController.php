<?php

namespace App\Http\Controllers;

use App\Exports\EBRTemplateExport;
use App\Imports\EBRTemplateImport;
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
    public function index(): \Inertia\Response
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
            'status' => 'processing',
        ]);

        $newFileName = $newEbr->id . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('ebr_files', $newFileName, 'local');

        // Pasar el UUID al importador para asociar con los datos
        $import = new EBRTemplateImport($newEbr->id);

        Excel::import($import, $path);

        return redirect()->route('ebr.index');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new EBRTemplateExport(), 'Plantilla EBR.xlsx');
    }

    public function downloadDemoEBR(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filePath = public_path('templates/EBRAgentesRelacionadosDemo.xlsx');

        return response()->download($filePath);
    }

}
