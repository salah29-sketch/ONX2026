@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">الحجوزات</h1>
        <div class="db-page-subtitle">مراجعة كل طلبات الحجز وتتبع حالتها.</div>
    </div>

    <a href="{{ route('admin.bookings.calendar') }}" class="db-btn-primary">
        <i class="fas fa-calendar-alt"></i>
        تقويم المراقبة
    </a>
</div>



<div class="db-filter-bar">
    <form method="GET">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-3 mb-2">
                <label class="db-label">الخدمة / الباقة</label>
                <select name="package_id" class="db-input">
                    <option value="">الكل</option>
                    @foreach($services ?? [] as $s)
                        <optgroup label="{{ $s->name }}">
                            @foreach($s->packages as $package)
                                <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="col-span-12 md:col-span-3 mb-2">
                <label class="db-label">الحالة</label>
                <select name="status" class="db-input">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="unconfirmed" {{ request('status') === 'unconfirmed' ? 'selected' : '' }}>غير مؤكد</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>تم التعيين</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغى</option>
                </select>
            </div>

            <div class="col-span-12 md:col-span-2 mb-2">
                <label class="db-label">من</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="db-input">
            </div>

            <div class="col-span-12 md:col-span-2 mb-2">
                <label class="db-label">إلى</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="db-input">
            </div>

            <div class="col-span-12 md:col-span-2 mb-2 flex items-end">
                <button class="db-btn-primary w-full justify-center">
                    <i class="fas fa-filter"></i>
                    فلترة
                </button>
            </div>
        </div>
    </form>
</div>

<div class="db-card">
    <div class="db-card-header">قائمة الحجوزات</div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>الخدمة</th>
                        <th>الباقة</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $booking->phone }}</td>
                            <td>{{ $booking->service?->name ?? '—' }}</td>
                            <td>{{ $booking->package?->name ?? '—' }}</td>
                            <td> @if($booking->event_date)
                                     {{ \Carbon\Carbon::parse($booking->event_date)->format('Y/m/d') }}
                                @else
                                      —
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusClass = match($booking->status) {
                                        'pending' => 'db-badge-new',
                                        'unconfirmed' => 'db-badge-new',
                                        'confirmed' => 'db-badge-confirmed',
                                        'assigned' => 'db-badge-confirmed',
                                        'in_progress' => 'db-badge-progress',
                                        'completed' => 'db-badge-completed',
                                        'cancelled' => 'db-badge-cancelled',
                                        default => 'db-badge-new'
                                    };
                                    $statusLabel = match($booking->status) {
                                        'pending' => 'قيد الانتظار',
                                        'unconfirmed' => 'غير مؤكد',
                                        'confirmed' => 'مؤكد',
                                        'assigned' => 'تم التعيين',
                                        'in_progress' => 'قيد التنفيذ',
                                        'completed' => 'مكتمل',
                                        'cancelled' => 'ملغى',
                                        default => $booking->status
                                    };
                                @endphp

                                <span class="db-badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td>{{ $booking->created_at?->format('Y-m-d H:i') }}</td>

                            <td>
                                <div class="db-actions">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="db-icon-btn db-view-btn" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="db-icon-btn db-delete-btn" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="db-empty">
                                    <i class="fas fa-calendar-times"></i>
                                    لا توجد حجوزات حاليًا.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection