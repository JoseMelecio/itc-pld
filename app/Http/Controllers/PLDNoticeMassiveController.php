<?php

namespace App\Http\Controllers;

use App\Http\Requests\PLDNoticeMassiveStoreRequest;
use App\Jobs\ProcessPLDNoticeMassive;
use App\Models\Permission;
use App\Models\PLDNotice;
use App\Models\PLDNoticeMassive;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PLDNoticeMassiveController extends Controller
{
    /**
     * Handles the retrieval and display of PLD massive notices.
     * and logs the authorized notices. Renders the 'pld-notice-massive/Index' view with the retrieved data.
     */
    public function index()
    {
        $pldMassives = PLDNoticeMassive::orderBy('created_at', 'ASC')->get();
        $notificationPldPermission = Permission::where('name', 'notification_pld')->first();
        $notices = Permission::where('permission_id', $notificationPldPermission->id)
            ->select('name')->get();
        $noticesByUser = $notices->pluck('name');
        $allowedNotices = PLDNotice::whereIn('route_param', $noticesByUser)
            ->where('allow_massive', true)
            ->get();

        return Inertia::render('pld-notice-massive/Index', [
            'pldMassives' => $pldMassives,
            'allowedNotices' => $allowedNotices,
        ]);
    }

    /**
     * Handles the download of a specific template file based on the given notice.
     *
     * @param string $notice The identifier for the notice used to locate the template.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse The response containing the file to download.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the specified notice is not found.
     */
    public function downloadTemplate(string $notice): BinaryFileResponse
    {
        $pldNotice = PLDNotice::where('route_param', $notice)->firstOrFail();
        $filePath = public_path('templates/'.$pldNotice->template);

        return response()->download($filePath);
    }

    public function store(PLDNoticeMassiveStoreRequest $request)
    {
        $templateFile = $request->file('template');
        $headerData = $request->validated();
        $headerData['obligated_subject'] = Auth::user()->tax_id;
        unset($headerData['template']);
        unset($headerData['notice_id']);

        $templateFileName = uniqid() . '.' . $templateFile->getClientOriginalExtension();

        $templateFile->storeAs('pld_massive', $templateFileName, 'local');

        $newMassiveNotice = PLDNoticeMassive::create([
            'user_id' => Auth::user()->id,
            'pld_notice_id' => $request->notice_id,
            'file_uploaded' => $templateFileName,
            'original_name' => $templateFile->getClientOriginalName(),
            'form_data' => $headerData,
            'status' => 'processing',
        ]);

        ProcessPLDNoticeMassive::dispatch($newMassiveNotice->id);

        return redirect()->route('notification-pld-massive.index');
    }
}
