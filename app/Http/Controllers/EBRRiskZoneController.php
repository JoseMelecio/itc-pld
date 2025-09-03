<?php

namespace App\Http\Controllers;

use App\Http\Requests\EBRRiskZoneStoreRequest;
use App\Models\EBRRiskZone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EBRRiskZoneController extends Controller
{
    public function index()
    {
        $riskzones = EBRRiskZone::all();

        return Inertia::render('ebr/catalogs/risk-zones/riskZoneIndex', [
            'riskZones' => $riskzones,
        ]);
    }

    public function store(EBRRiskZoneStoreRequest $request)
    {
        $data = $request->validated();
        EBRRiskZone::create($data);

        return redirect()->route('ebr.riskZones.index');
    }

    public function update(EBRRiskZoneStoreRequest $request, $id)
    {
        $riskzone = EBRRiskZone::findOrFail($id);
        $data = $request->validated();

        $riskzone->update($data);

        return redirect()->route('ebr.riskZones.index');
    }

    public function destroy($id)
    {
        $riskzone = EBRRiskZone::findOrFail($id);
        $riskzone->delete();

        return redirect()->route('ebr.riskZones.index');
    }


}
