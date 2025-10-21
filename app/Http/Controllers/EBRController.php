<?php

namespace App\Http\Controllers;

use App\Exports\EBRClientExport;
use App\Exports\EBRMultiSheetExport;
use App\Exports\EBRRiskInherentExport;
use App\Exports\EBROperationExport;
use App\Http\Requests\EBRConfigurationStoreRequest;
use App\Http\Requests\EBRStoreRequest;
use App\Imports\EBRClientImport;
use App\Imports\EBROperationImport;
use App\Jobs\FinalizeEBRProcessingJob;
use App\Jobs\ImportClientsFileJob;
use App\Jobs\ImportOperationsFileJob;
use App\Models\EBR;
use App\Models\EBRConfiguration;
use App\Models\EBRRiskElementIndicatorRelated;
use App\Models\EBRRiskElementRelated;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


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
            'user_id' => auth()->user()->id,
            'file_name_clients' => $fileClients->getClientOriginalName(),
            'file_name_operations' => $fileOperations->getClientOriginalName(),
            'status' => 'processing',
        ]);

        $clientsFileName = $newEbr->id . '_clients.' . $fileClients->getClientOriginalExtension();
        $operationsFileName = $newEbr->id . '_operations.' . $fileOperations->getClientOriginalExtension();

        $clientsPath = $fileClients->storeAs('ebr_files', $clientsFileName, 'local');
        $operationsPath = $fileOperations->storeAs('ebr_files', $operationsFileName, 'local');

        Excel::queueImport(new EBRClientImport(
            $newEbr->id,
            auth()->user()->id), $clientsPath);

        Excel::queueImport(new EBROperationImport(
            $newEbr->id,
            auth()->user()->id), $operationsPath);

//        Bus::batch([
//            new ImportClientsFileJob($newEbr->id, $clientsPath),
//            new ImportOperationsFileJob($newEbr->id, $operationsPath),
//        ])
//            ->then(function (Batch $batch) use ($newEbr) {
//                // Aquí todo terminó con éxito.
//                FinalizeEBRProcessingJob::dispatch($newEbr->id);
//            })
//            ->catch(function (Batch $batch, Throwable $e) {
//                Log::error('Error en la importación: '.$e->getMessage());
//            })
//            ->dispatch();

        //ImportClientsFileJob::dispatch($newEbr->id, $clientsPath, $operationsPath);


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

    /**
     * Display the configuration page for the EBR module.
     *
     * @return \Inertia\Response
     */
    public function configuration()
    {
        $ebrConfiguration = EBRConfiguration::where('user_id', auth()->user()->id)
            ->first();

        return Inertia::render('ebr/Configuration', [
            'configs' => [
                'template_clients_config' => $ebrConfiguration ? implode(",\n", $ebrConfiguration->template_clients_config) : '',
                'template_operations_config' => $ebrConfiguration ? implode(",\n", $ebrConfiguration->template_operations_config) : '',
            ]
        ]);
    }

    public function configurationStore(EBRConfigurationStoreRequest $request)
    {
        $data = $request->validated();
        $ebrConfiguration = EBRConfiguration::where('user_id', $data['user_id'])
            ->first();

        if ($ebrConfiguration) {
            $ebrConfiguration->update($data);
        } else {
            EBRConfiguration::create($data);
        }

        return redirect()->route('ebr.configurations');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function calcs()
    {
        $ebrId = 1;
        $ebr = EBR::findOrFail($ebrId);
        $ebr->total_operation_amount = $ebr->total_amount;
        $ebr->total_clients = $ebr->total_clients_count;
        $ebr->total_operations = $ebr->total_operations_count;
        $ebr->maximum_risk_level = $ebr->maximum_risk_level_count;

        //Risk elemetns
        foreach ($ebr->type->riskElements as $riskElement) {
            $dataCalculated = $riskElement->calculate($ebr->id);
            foreach ($dataCalculated as $value) {
                $newElement = [
                    'ebr_id' => $ebr->id,
                    'ebr_risk_element_id' => $riskElement->id,
                    'element' => $value['risk_element'],
                    'amount_mxn' => $value['amount_mxn'],
                    'total_clients' => $value['total_clients'],
                    'total_operations' => $value['total_operations'],
                    'weight_range_impact' => ($value['amount_mxn'] / $ebr->total_operation_amount) * 100,
                    'frequency_range_impact' => 0,
                    'risk_inherent_concentration' => 0,
                    'risk_level_features' => 0,
                    'risk_level_integrated' => 0,
                ];
                //EBRRiskElementRelated::create($newElement);
            }
        }

        //Risk elements indicators
        foreach ($ebr->type->riskElements as $riskElement) {
            foreach ($riskElement->riskIndicatorRelated as $riskIndicator) {
//                EBRRiskElementIndicatorRelated::create([
//                    'ebr_id' => $ebr->id,
//                    'ebr_risk_element_indicator_id' => $riskElement->id,
//                    'characteristic' => $riskIndicator->characteristic,
//                    'key' => $riskIndicator->key,
//                    'name' => $riskIndicator->name,
//                    'description' => $riskIndicator->description,
//                    'risk_indicator' => $riskIndicator->risk_indicator,
//                    'order' => $riskIndicator->order,
//                    'amount' => rand(1, 1000),
//                    'related_clients' => rand(1, 1000),
//                    'related_operations' =>rand(1, 1000),
//                    'weight_range_impact' => rand(1, 100),
//                    'frequency_range_impact' => rand(1, 100),
//                    'characteristic_concentration' => rand(1, 100),
//                ]);
            }
        }

        $ebr->save();


        //return Excel::download(new EBRMultiSheetExport($ebr), 'reporte_ebr.xlsx');
        //return view('exports.ebr_summary')->with('ebr', $ebr);


        // 1. Ruta temporal
        $tempFile = 'temp_reporte_ebr.xlsx';
        $publicFile = 'public/reporte_ebr_grafico.xlsx';

        // 2. Guardar con Laravel Excel (sin gráfico aún, pero con AfterSheet preparado)

        $disk = Storage::disk('local'); // 'local' apunta a storage/app

        $tempFile = 'temp_reporte_ebr.xlsx';
        Excel::store(new EBRMultiSheetExport($ebr), $tempFile, 'local', null, ['includeCharts' => true]);

        // ✅ Confirmar que el archivo fue guardado
        $tempPath = $disk->path($tempFile);
        if (!file_exists($tempPath)) {
            abort(500, "Archivo Excel temporal no fue creado: {$tempPath}");
        }

        // 3. Reabrir con PhpSpreadsheet
        $spreadsheet = IOFactory::load(storage_path("app/private/{$tempFile}"));

        // 4. Guardar con gráfico incluido
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true); // 👈 Esto activa los gráficos

        $finalPath = storage_path("app/{$publicFile}");
        $writer->save($finalPath);

        // 5. Descargar
        return response()->download($finalPath)->deleteFileAfterSend(true);
    }

    public function graficos()
    {
        return view('ebr.graficos');
    }

    public function exportPDF(Request $request)
    {
        $chartImage = $request->input('chart_image');

        return Pdf::loadView('chart-pdf', compact('chartImage'))
            ->download('grafico_dispersión.pdf');
    }
}
