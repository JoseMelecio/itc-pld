<?php

namespace App\Services;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SystemLogService
{
    public static function add($data): void
    {
        SystemLog::create([
            'user_id' => Auth::user()->id,
            'tenant_id' => $data['tenant_id'],
            'model_type' => $data['model_type'],
            'model_id' => $data['model_id'],
            'type' => $data['type'],
            'content' => $data['content'],
        ]);
    }
}
