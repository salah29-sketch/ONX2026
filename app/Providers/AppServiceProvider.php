<?php

namespace App\Providers;

use App\Models\Client\ClientMessagesSeen;
use App\Models\Content\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->share('data', 'starter');

        view()->composer('*', function ($view) {
            $companySettings = Company::first();
            $view->with('companySettings', $companySettings);
        });

        // بيانات بوابة العملاء للـ sidebar و bottom nav
        View::composer('client.layout', function ($view) {
            if (!Auth::guard('client')->check()) {
                return;
            }
            $client      = Auth::guard('client')->user();
            $unreadQuery = $client->messages()->whereNull('admin_read_at');
            $lastSeen    = ClientMessagesSeen::where('client_id', $client->id)->value('last_seen_at');
            if ($lastSeen) {
                $unreadQuery->where('created_at', '>', $lastSeen);
            }
            $unreadMessages = $unreadQuery->count();
            $activeBooking  = $client->bookings()
                ->whereIn('status', ['confirmed', 'in_progress'])
                ->latest()->first();
            if (!$activeBooking) {
                $activeBooking = $client->bookings()->latest()->first();
            }
            $canReview = $client->bookings()->where('status', 'completed')->exists();
            $view->with(compact('client', 'unreadMessages', 'activeBooking', 'canReview'));
        });
    }
}
