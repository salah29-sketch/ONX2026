<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: base_path('routes/web.php'),
        api: base_path('routes/api.php'),
        commands: base_path('routes/console.php'),
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->prepend([
            \App\Http\Middleware\TrustProxies::class,
        ]);

        // Web middleware group — append custom middleware
        $middleware->web(append: [
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        // API middleware group
        $middleware->api(append: [
            \App\Http\Middleware\AuthGates::class,
        ]);

        // Route middleware aliases
        $middleware->alias([
            'only.iframe'  => \App\Http\Middleware\OnlyIframe::class,
            'client.auth'  => \App\Http\Middleware\RedirectIfNotClient::class,
            'worker.auth'  => \App\Http\Middleware\RedirectIfNotWorker::class,
            'admin.audit'  => \App\Http\Middleware\AdminAuditLog::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تم إرسال عدد كبير من الطلبات. يرجى الانتظار دقيقة ثم المحاولة مرة أخرى.',
                ], 429);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'throttle' => 'تم إرسال عدد كبير من الطلبات. يرجى الانتظار دقيقة ثم المحاولة مرة أخرى.',
                ]);
        });

        $exceptions->renderable(function (PostTooLargeException $e, $request) {
            $message = 'حجم الملفات أو البيانات المرسلة أكبر من المسموح. قلّل حجم الصور أو عددها، أو راجع إعدادات الخادم (post_max_size و upload_max_filesize في php.ini).';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }
            return redirect()->back()->withErrors(['post_size' => $message]);
        });

        $exceptions->dontFlash([
            'password',
            'password_confirmation',
        ]);
    })
    ->create();
