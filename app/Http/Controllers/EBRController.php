<?php

namespace App\Http\Controllers;

use App\Exports\EBRClientExport;
use App\Exports\EBRMultiSheetExport;
use App\Exports\EBROperationExport;
use App\Http\Requests\EBRConfigurationStoreRequest;
use App\Http\Requests\EBRRiskelementConfigurationStoreRequest;
use App\Http\Requests\EBRStoreRequest;
use App\Imports\EBRClientImport;
use App\Imports\EBROperationImport;
use App\Models\EBR;
use App\Models\EBRConfiguration;
use App\Models\EBRRiskElement;
use App\Models\EBRRiskElementIndicatorRelated;
use App\Models\EBRRiskElementRelated;
use App\Models\EBRRiskElementRelatedAverage;
use App\Services\JsonQueryBuilder;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $usersDebugMode = ['admin', 'jmelecio', 'oayaquica'];
        $ebrs = EBR::all();
        $ebrTypeUser = auth()->user()->ebrTypes;

        return Inertia::render('ebr/Index', [
            'ebrs' => $ebrs,
            'ebrTypeUser' => $ebrTypeUser,
            'show_debug_mode' => in_array(Auth::user()->user_name, $usersDebugMode),
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
    public function showConfiguration() : \Inertia\Response
    {
        return Inertia::render('ebr/Configuration', $this->configuration());
    }

    public function configurationStore(EBRConfigurationStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $ebrConfiguration = EBRConfiguration::where('user_id', Auth::user()->id)->first();

        if ($ebrConfiguration) {
            $ebrConfiguration->update($data);
        } else {
            EBRConfiguration::create($data);
        }

        return Inertia::render('ebr/Configuration', $this->configuration());
    }

    public function riskElementConfigurationStore(EBRRiskElementConfigurationStoreRequest $request)
    {
        $data = $request->validated();
        $user_id = Auth::user()->id;

        $ebrConfiguration = EBRConfiguration::where('user_id', $user_id)->first();
        $ebrConfiguration->riskElements()->sync($data['risk_element_config']);

        return Inertia::render('ebr/Configuration', $this->configuration());
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function calcs($id)
    {
        $ebr = EBR::findOrFail($id);
        $ebr->total_operation_amount = $ebr->total_amount;
        $ebr->total_clients = $ebr->total_clients_count;
        $ebr->total_operations = $ebr->total_operations_count;
        $ebr->maximum_risk_level = $ebr->maximum_risk_level_count;

        $ebrConfiguration = EBRConfiguration::where('user_id', $ebr->user_id)->first();
        foreach ($ebrConfiguration->riskElements as $riskElement) {
            $builder = new JsonQueryBuilder($riskElement->report_config, $ebr->id);
            $query = $builder->build();
            $result = $query->get();

            $average_risk_inherent_concentration = [];
            $average_risk_level_features = [];
            $average_risk_level_integrated = [];
            $weight_impact_range_header = [];
            $frequency_range_header = [];
            $risk_inherent_concentration_header = [];

            foreach ($result as $item) {

                $newElement = [
                    'ebr_id' => $ebr->id,
                    'ebr_risk_element_id' => $riskElement->id,
                    'element' => $item->label,
                    'amount_mxn' => $item->amount_mxn,
                    'total_clients' => $item->total_clients,
                    'total_operations' => $item->total_operations,
                    'weight_range_impact' => ($item->amount_mxn / $ebr->total_operation_amount) * 100,
                    'frequency_range_impact' => ((($item->total_operations / $ebr->total_operations) + ($item->total_clients / $ebr->total_clients)) / 2) * 100,
                    'risk_level_features' => 0,
                    'risk_level_integrated' => 0,
                ];
                $newElement['risk_inherent_concentration'] = ($newElement['weight_range_impact'] + $newElement['frequency_range_impact']) / 2;

                $average_risk_inherent_concentration[] = $newElement['risk_inherent_concentration'];
                $average_risk_level_features[] = 0;
                $average_risk_level_integrated[] = 0;
                $weight_impact_range_header[] = $item->amount_mxn;
                $frequency_range_header[] = $item->total_operations;

                EBRRiskElementRelated::create($newElement);
            }

            $relatedAverage = EBRRiskElementRelatedAverage::create([
                'ebr_id' => $ebr->id,
                'ebr_risk_element_id' => $riskElement->id,
                'average_risk_inherent_concentration' => collect($average_risk_inherent_concentration)->avg(),
                'average_risk_level_features' => collect($average_risk_level_features)->avg(),
                'average_risk_level_integrated' => collect($average_risk_level_integrated)->avg(),
                'weight_impact_range_header' => collect($weight_impact_range_header)->sum() / $ebr->total_operation_amount,
                'frequency_range_header' => collect($frequency_range_header)->sum() / $ebr->total_operations,
            ]);

            $relatedAverage['risk_inherent_concentration_header'] = ($relatedAverage['weight_impact_range_header'] + $relatedAverage['frequency_range_header']) / 2;
        }

        $ebr->save();
        return Excel::download(new EBRMultiSheetExport($ebr), 'reporte_ebr.xlsx');
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

    /**
     * @return array
     */
    public function configuration(): array
    {
        $ebrConfiguration = EBRConfiguration::where('user_id', auth()->user()->id)->first();
        if (!$ebrConfiguration) {
            $ebrConfiguration = EBRConfiguration::create([
                'user_id' => auth()->user()->id,
                'template_clients_config' => [],
                'template_operations_config' => [],
            ]);
            $ebrConfiguration->riskElements()->attach(EBRRiskElement::first()->id);
        }

        $clientsFields = array_diff(
            Schema::getColumnListing('ebr_clients'), EBRRiskElement::DONT_SHOW_FILES_IN_EXCEL
        );
        $operationsFields = array_diff(
            Schema::getColumnListing('ebr_operations'), EBRRiskElement::DONT_SHOW_FILES_IN_EXCEL
        );


        $riskElements = EBRRiskElement::where('active', true)->orderBy('risk_element')->get();

        $riskElementSelectedIds = $ebrConfiguration->riskElements()->pluck('ebr_risk_elements.id')->toArray();

        return [
            'templates' => [
                'clients' => $clientsFields,
                'operations' => $operationsFields,
            ],
            'ebr_configuration' => [
                'clients' => $ebrConfiguration->template_clients_config,
                'operations' => $ebrConfiguration->template_operations_config,
            ],
            'risk_elements' => $riskElements,
            'risk_elements_selected' => $riskElementSelectedIds
        ];
    }
}
