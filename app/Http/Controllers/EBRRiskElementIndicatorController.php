<?php

namespace App\Http\Controllers;

use App\Http\Requests\EBRRiskElementStoreRequest;
use App\Http\Requests\EBRRiskIndicatorStoreRequest;
use App\Models\EBRRiskElement;
use App\Models\EBRRiskElementIndicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use function Pest\Laravel\json;

class EBRRiskElementIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riskIndicators = EBRRiskElementIndicator::all();
        Log::info(json_encode($riskIndicators));

        return Inertia::render('ebr/catalogs/risk-element-indicators/Index', [
            'riskIndicators' => $riskIndicators,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ebr/catalogs/risk-element-indicators/Edit', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EBRRiskIndicatorStoreRequest $request)
    {
        $validated = $request->validated();
        EBRRiskElementIndicator::create($validated);

        return redirect()->route('ebr.riskElementIndicators.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $riskIndicator = EBRRiskElementIndicator::findOrFail($id);

        return Inertia::render('ebr/catalogs/risk-element-indicators/Edit', [
            'riskIndicator' => $riskIndicator,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EBRRiskIndicatorStoreRequest $request, string $id)
    {
        $validated = $request->validated();
        $riskIndicator = EBRRiskElementIndicator::findOrFail($id);
        $riskIndicator->update($validated);

        return redirect()->route('ebr.riskElementIndicators.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $riskIndicator = EBRRiskElementIndicator::findOrFail($id);
        $riskIndicator->delete();

        return redirect()->route('ebr.riskElementIndicators.index');
    }
}
