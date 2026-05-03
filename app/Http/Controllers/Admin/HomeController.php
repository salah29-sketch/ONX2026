<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking\Booking;
use App\Models\Client\Client;
use App\Models\Client\ClientMessage;
use App\Models\Content\Message;
use App\Models\Content\PortfolioItem;
use App\Models\Service\Package;

class HomeController
{
    public function index()
    {
        $bookingsCount = Booking::count();
        $clientsCount = Client::count();
        $portfolioCount = PortfolioItem::count();
        $unconfirmedBookingsCount = Booking::whereIn('status', ['unconfirmed', 'new', 'pending'])->count();
        $unreadMessagesCount = ClientMessage::whereNull('admin_read_at')->count();
        $unreadOfferMessages = Message::where('status', 'new')->count();
        $recentBookings = Booking::with(['client', 'service', 'package'])
            ->latest('created_at')
            ->take(7)
            ->get();

        // إحصائيات حسب الباقات
        $packageStats = Package::withCount(['bookings'])
            ->where('is_active', true)
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'bookingsCount',
            'clientsCount',
            'portfolioCount',
            'unconfirmedBookingsCount',
            'unreadMessagesCount',
            'unreadOfferMessages',
            'recentBookings',
            'packageStats'
        ));
    }
}
