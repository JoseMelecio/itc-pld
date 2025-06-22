<?php

namespace App\Http\Controllers;

use App\Exports\EBRClientExport;
use App\Exports\EBROperationExport;
use App\Http\Requests\EBRStoreRequest;
use App\Imports\EBRTemplateImport;
use App\Jobs\ImportClientsFileJob;
use App\Jobs\ImportOperationsFileJob;
use App\Models\EBR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Exception;

class EBRController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $ebrs = EBR::all();
        $ebrTypeUser = auth()->user()->ebrTypes;

        return Inertia::render('ebr/Index', [
            'ebrs' => $ebrs,
            'ebrTypeUser' => $ebrTypeUser,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EBRStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $fileClients = $request->file('file_clients');
        $fileOperations = $request->file('file_operations');

        $newEbr = EBR::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->user()->id,
            'file_name_clients' => $fileClients->getClientOriginalName(),
            'file_name_operations' => $fileOperations->getClientOriginalName(),
            'status' => 'processing',
            'ebr_type_id' => $request->ebr_type_id,
        ]);

        $clientsFileName = $newEbr->id . '_clients.' . $fileClients->getClientOriginalExtension();
        $operationsFileName = $newEbr->id . '_operations.' . $fileOperations->getClientOriginalExtension();

        $clientsPath = $fileClients->storeAs('ebr_files', $clientsFileName, 'local');
        $operationsPath = $fileOperations->storeAs('ebr_files', $operationsFileName, 'local');

        ImportClientsFileJob::withChain([
            new ImportOperationsFileJob($newEbr->id, $operationsPath),
        ])->dispatch($newEbr->id, $clientsPath);


        return redirect()->route('ebr.index');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadClientTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new EBRClientExport(), 'Plantilla Clientes EBR.xlsx');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadOperationTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new EBROperationExport(), 'Plantilla Operaciones EBR.xlsx');
    }

    public function downloadDemoEBR(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filePath = public_path('templates/EBRAgentesRelacionadosDemo.xlsx');

        return response()->download($filePath);
    }

}
