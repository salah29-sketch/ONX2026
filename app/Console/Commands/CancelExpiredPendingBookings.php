<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking\Booking;
use Carbon\Carbon;

class CancelExpiredPendingBookings extends Command
{
    protected $signature = 'bookings:cancel-expired-pending';
    protected $description = 'Cancel pending event bookings older than 7 days without confirmation';

    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(7);

        $expiredBookings = Booking::whereHas('service', fn ($q) => $q->where('slug', 'events'))
            ->whereIn('status', ['pending', 'new'])
            ->where('created_at', '<=', $cutoffDate)
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => 'cancelled',
            ]);
            $count++;
        }

        $this->info("Expired pending bookings cancelled: {$count}");

        return Command::SUCCESS;
    }
}