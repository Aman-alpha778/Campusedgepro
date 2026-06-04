<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ActivityLogService
{
    public function record(string $action, string $module, ?string $description = null, ?Request $request = null): void
    {
        try {
            if (! Schema::hasTable('activity_logs')) {
                return;
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => $request?->ip() ?? request()?->ip(),
            ]);
        } catch (Throwable) {
            return;
        }
    }
}
