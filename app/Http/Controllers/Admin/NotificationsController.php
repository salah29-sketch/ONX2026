<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Content\Message;
use Carbon\Carbon;

class NotificationsController extends Controller
{
    public function latest()
    {
        $since = request('since');

        $bookingQuery = Booking::with(['client:id,name,phone', 'service:id,name'])
            ->orderByDesc('created_at')
            ->limit(10);

        if ($since) {
            $bookingQuery->where('created_at', '>', Carbon::createFromTimestamp($since));
        } else {
            $bookingQuery->where('created_at', '>', now()->subMinutes(30));
        }

        $bookings = $bookingQuery->get()->map(fn ($b) => [
            'id'         => $b->id,
            'name'       => $b->client?->name ?? $b->name,
            'phone'      => $b->client?->phone ?? $b->phone,
            'service'    => $b->service?->name ?? '—',
            'status'     => $b->status?->value ?? $b->status,
            'created_at' => $b->created_at->timestamp,
            'url'        => route('admin.bookings.show', $b->id),
        ]);

        $messageQuery = Message::where('status', 'new')
            ->orderByDesc('created_at')
            ->limit(5);

        if ($since) {
            $messageQuery->where('created_at', '>', Carbon::createFromTimestamp($since));
        }

        $messages = $messageQuery->get()->map(fn ($m) => [
            'id'         => $m->id,
            'name'       => $m->displayName(),
            'subject'    => $m->subject,
            'created_at' => $m->created_at->timestamp,
            'url'        => route('admin.messages.show', $m->id),
        ]);

        return response()->json([
            'bookings'     => $bookings,
            'messages'     => $messages,
            'unread_count' => Message::where('status', 'new')->count(),
            'server_ts'    => now()->timestamp,
        ]);
    }
}
