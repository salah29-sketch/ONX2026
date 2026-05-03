<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['client', 'service', 'eventBooking.venue'])
            ->whereHas('service', fn ($q) => $q->where('slug', 'events'));

        $dateFilter = $request->get('date', 'all');
        if ($dateFilter === 'today') {
            $query->whereDate('event_date', Carbon::today());
        } elseif ($dateFilter === 'upcoming') {
            $query->where('event_date', '>=', Carbon::today());
        }

        $statusFilter = $request->get('status');
        if ($statusFilter && in_array($statusFilter, ['unconfirmed', 'confirmed', 'in_progress', 'completed', 'cancelled'], true)) {
            $query->where('status', $statusFilter);
        }

        $bookings = $query->orderByDesc('event_date')->paginate(15)->withQueryString();

        return view('worker.dashboard', compact('bookings', 'dateFilter', 'statusFilter'));
    }
}
