<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity for the current user.
     *
     * @param  string  $action
     * @param  string|null  $description
     * @return void
     */
    public static function log(string $action, ?string $description = null)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => Request::ip(),
            ]);
        }
    }
}
