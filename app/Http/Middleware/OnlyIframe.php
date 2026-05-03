<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyIframe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, \Closure $next)
{
    // Require authenticated session — Referer header alone is forgeable
    if (!auth()->check()) {
        abort(403, 'غير مصرح بالدخول المباشر إلى هذه الصفحة.');
    }

    $referer = $request->headers->get('referer');

    if (!$referer || !str_contains($referer, url('/'))) {
        abort(403, 'غير مصرح بالدخول المباشر إلى هذه الصفحة.');
    }

    return $next($request);
}
}
