@forelse($bookings as $booking)
    <div class="card" data-date="{{ $booking->event_date?->format('Y-m-d') }}">
        <h3>
            {{ $booking->package?->name ?? $booking->service?->name ?? '—' }}
            <span class="badge badge-{{ $booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : $booking->status }}" style="margin-inline-start:.4rem;">
                {{ $statusLabels[$booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : $booking->status] ?? $booking->status }}
            </span>
        </h3>
        <div class="meta">
            <span><i class="fas fa-user"></i> {{ $booking->client?->name ?? $booking->name ?? '—' }}</span>
            <span><i class="fas fa-phone"></i> {{ $booking->phone ?? '—' }}</span>
            @if($booking->event_date)
                <span><i class="fas fa-calendar"></i> {{ $booking->event_date->format('Y-m-d') }}</span>
            @endif
            @if($booking->eventBooking?->venue_custom)
                <span><i class="fas fa-map-marker-alt"></i> {{ $booking->eventBooking->venue_custom }}</span>
            @endif
            @if($booking->eventBooking?->start_time)
                <span><i class="fas fa-clock"></i> {{ $booking->eventBooking->start_time }}</span>
            @endif
        </div>
        @if($booking->notes)
            <p style="font-size:.8rem;color:rgba(255,255,255,.4);margin-top:.5rem;">{{ $booking->notes }}</p>
        @endif
    </div>
@empty
    <div class="empty-state">
        <i class="fas fa-calendar-times" style="font-size:2rem;margin-bottom:.75rem;display:block;"></i>
        لا توجد حجوزات.
    </div>
@endforelse