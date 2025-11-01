<?php

namespace App\Http\Controllers;

use App\Http\Requests\EBRRiskElementStoreRequest;
use App\Models\EBRRiskElement;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class EBRRiskElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riskElements = EBRRiskElement::all();

        return Inertia::render('ebr/catalogs/risk-elements/Index', [
            'riskElements' => $riskElements,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ebr/catalogs/risk-elements/Edit', [
            'groupFields' => $this->fullColumnNameList(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EBRRiskElementStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['report_config'] = EBRRiskElement::DEFAULT_REPORT_RULES;
        $validated['report_config']['group_by'][] = $validated['grouper_field'];
        $validated['report_config']['selects'][] = [
            'type' => 'select',
            'alias' => 'label',
            'value' => $validated['grouper_field']
        ];
        EBRRiskElement::create($validated);

        return redirect()->route('ebr.riskElements.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $riskElement = EBRRiskElement::findOrFail($id);

        return Inertia::render('ebr/catalogs/risk-elements/Edit', [
            'riskElement' => $riskElement,
            'groupFields' => $this->fullColumnNameList(),
            'grouper_field' => $riskElement['report_config']['group_by'][0],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EBRRiskElementStoreRequest $request, string $id)
    {
        $validated = $request->validated();
        $riskElement = EBRRiskElement::findOrFail($id);
        $reportConfig = $riskElement['report_config'];
        $reportConfig['group_by'] = [$validated['grouper_field']];
        $reportConfig['selects'][] = [
            'type' => 'select',
            'alias' => 'label',
            'value' => $validated['grouper_field']
        ];
        $validated['report_config'] = $reportConfig;

        $riskElement->update($validated);

        return redirect()->route('ebr.riskElements.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $riskElement = EBRRiskElement::findOrFail($id);
        $riskElement->delete();

        return redirect()->route('ebr.riskElements.index');
    }

    /**
     * Generates a fully qualified column name list by combining and sorting the
     * filtered column listings from the 'ebr_clients' and 'ebr_operations' tables.
     *
     * @return array The sorted array of fully qualified column names.
     */
    public function fullColumnNameList(): array
    {
        $clientsFields = array_diff(
            Schema::getColumnListing('ebr_clients'), EBRRiskElement::DONT_SHOW_FILES_IN_EXCEL
        );
        $operationsFields = array_diff(
            Schema::getColumnListing('ebr_operations'), EBRRiskElement::DONT_SHOW_FILES_IN_EXCEL
        );
        $fullNameClientsFields = array_map(fn($campo) => "ebr_clients.$campo", $clientsFields);
        $fullNameOperationsFields = array_map(fn($campo) => "ebr_operations.$campo", $operationsFields);
        $ebrGroupFields = array_merge($fullNameClientsFields, $fullNameOperationsFields);

        return Arr::sort($ebrGroupFields);
    }
}
