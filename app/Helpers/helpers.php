<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logActivity')) {
    /**
     * Log user activity to activity_logs table.
     *
     * @param string $action
     * @param mixed $model
     * @param string $description
     * @return void
     */
    function logActivity($action, $model, $description)
    {
        ActivityLog::create([
            'model' => get_class($model),
            'model_id' => $model->id,
            'action' => $action,
            'user_id' => Auth::id(),
            'user_name' => Auth::check() ? Auth::user()->name : 'Guest',
            'description' => $description,
        ]);
    }
}
