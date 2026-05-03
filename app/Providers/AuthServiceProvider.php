<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function register(): void
    {
        parent::register();
    }

    public function boot(): void
    {
        // Passport: مثبت ولكنه غير مستخدم حالياً (API يستخدم Sanctum).
    }
}
