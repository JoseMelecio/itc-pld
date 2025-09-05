<?php

namespace App\Http\Controllers;

use App\Http\Requests\SystemLogDatesRequest;
use App\Http\Resources\SystemLogPldNoticeResource;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class SystemLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pldNotices(SystemLogDatesRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['start_date'])) {
            $validated['start_date'] = Carbon::now()->subMonth()->toDateString();
        }

        if (empty($validated['end_date'])) {
            $validated['end_date'] = Carbon::now()->toDateString();
        }

        $logs = SystemLog::whereBetween('created_at', [
            $validated['start_date'] . ' 00:00:00',
            $validated['end_date'] . ' 23:59:59',
        ])->get();

        return Inertia::render('logs/PldNoticeIndex', [
            'logs' => SystemLogPldNoticeResource::collection($logs),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemLog $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemLog $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemLog $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemLog $log)
    {
        //
    }
}
