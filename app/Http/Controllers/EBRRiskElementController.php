<?php

namespace App\Http\Controllers;

use App\Http\Requests\EBRRiskElementStoreRequest;
use App\Models\EBRRiskElement;
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
        return Inertia::render('ebr/catalogs/risk-elements/Edit', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EBRRiskElementStoreRequest $request)
    {
        $validated = $request->validated();
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
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EBRRiskElementStoreRequest $request, string $id)
    {
        $validated = $request->validated();
        $riskElement = EBRRiskElement::findOrFail($id);
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
}
