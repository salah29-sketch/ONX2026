<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * يسجّل كل عملية تعديل (POST/PUT/PATCH/DELETE) تجري في لوحة الإدارة.
 * السجلات تُكتب في storage/logs/laravel.log تحت channel 'stack'.
 */
class AdminAuditLog
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $user = auth()->user();
            Log::info('[ADMIN_AUDIT]', [
                'user_id'    => $user?->id,
                'user_email' => $user?->email,
                'method'     => $request->method(),
                'path'       => $request->path(),
                'ip'         => $request->ip(),
                'status'     => $response->getStatusCode(),
                'at'         => now()->toDateTimeString(),
            ]);
        }

        return $response;
    }
}
