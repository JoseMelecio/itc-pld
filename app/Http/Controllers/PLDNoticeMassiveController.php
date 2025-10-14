<?php

namespace App\Http\Controllers;

use App\Http\Requests\PLDNoticeMassiveStoreRequest;
use App\Jobs\ProcessPLDNoticeMassive;
use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\PLDNoticeMassive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PLDNoticeMassiveController extends Controller
{
    /**
     * Handles the retrieval and display of PLD massive notices.
     * Retrieves tenant-specific notices and permissions, filters allowed notices for the tenant,
     * and logs the authorized notices. Renders the 'pld-notice-massive/Index' view with the retrieved data.
     */
    public function index()
    {
        $pldMassives = PLDNoticeMassive::orderBy('created_at', 'ASC')->get();
        $tenant = Auth()->user()->tenant_id;
        $notificationPldPermission = Permission::where('name', 'notification_pld')->where('tenant_id', $tenant)->first();
        $notices = Permission::where('permission_id', $notificationPldPermission->id)
            ->select('name')->get();
        $noticesByUser = $notices->pluck('name');
        $allowedNotices = PLDNotice::whereIn('route_param', $noticesByUser)
            ->where('tenant_id', $tenant)
            ->where('allow_massive', true)
            ->get();
        Log::info($allowedNotices);

        return Inertia::render('pld-notice-massive/Index', [
            'pldMassives' => $pldMassives,
            'allowedNotices' => $allowedNotices,
        ]);
    }

    /**
     * Handles the download of a template file associated with a specific notice.
     *
     * @param string $notice The notice identifier used to locate the template file.
     * @return BinaryFileResponse The response object that initiates the file download.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the specified notice is not found for the tenant.
     */
    public function downloadTemplate(string $notice): BinaryFileResponse
    {
        $tenantId = \Auth::user()->tenant_id;
        $pldNotice = PLDNotice::where('route_param', $notice)->where('tenant_id', $tenantId)->firstOrFail();
        $filePath = public_path('templates/'.$pldNotice->template);

        return response()->download($filePath);
    }

    public function store(PLDNoticeMassiveStoreRequest $request)
    {
        $templateFile = $request->file('template');
        $templateFileName = uniqid() . '.' . $templateFile->getClientOriginalExtension();

        $templateFile->storeAs('pld_massive', $templateFileName, 'local');

        $newMassiveNotice = PLDNoticeMassive::create([
            'user_id' => \Auth::user()->id,
            'pld_notice_id' => $request->notice_id,
            'file_uploaded' => $templateFileName,
            'original_name' => $templateFile->getClientOriginalName(),
            'status' => 'processing',
        ]);

        ProcessPLDNoticeMassive::dispatch($newMassiveNotice->id);

        return redirect()->route('notification-pld-massive.index');
    }
}
