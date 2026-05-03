@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">إحصائيات الباقات</h1>
        <div class="db-page-subtitle">حجوزات مربوطة بـ package_id — فلتر الجداول حسب الفترة.</div>
    </div>
</div>

<div class="db-dash-row mb-4">
    <div class="db-dash-stat">
        <div class="db-dash-stat-body">
            <div class="db-dash-stat-icon primary"><i class="fas fa-box-open"></i></div>
            <div>
                <div class="db-dash-stat-num">{{ number_format($activePackages) }}</div>
                <div class="db-dash-stat-label">باقات مفعّلة</div>
            </div>
        </div>
    </div>
    <div class="db-dash-stat">
        <div class="db-dash-stat-body">
            <div class="db-dash-stat-icon success"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="db-dash-stat-num">{{ number_format($bookingsThisMonth) }}</div>
                <div class="db-dash-stat-label">حجوزات هذا الشهر (غير ملغاة)</div>
            </div>
        </div>
    </div>
    <div class="db-dash-stat">
        <div class="db-dash-stat-body">
            <div class="db-dash-stat-icon warning"><i class="fas fa-trophy"></i></div>
            <div>
                <div class="db-dash-stat-num">{{ $topPackageName }}</div>
                <div class="db-dash-stat-label">الأكثر حجزاً هذا الشهر — {{ number_format($topPackageBookings) }} حجز</div>
            </div>
        </div>
    </div>
    <div class="db-dash-stat">
        <div class="db-dash-stat-body">
            <div class="db-dash-stat-icon info"><i class="fas fa-chart-line"></i></div>
            <div>
                <div class="db-dash-stat-num">
                    {{ $avgBookingValue !== null ? number_format((float) $avgBookingValue, (int) config('currency.decimal_places', 0)) : '—' }}
                </div>
                <div class="db-dash-stat-label">متوسط قيمة الحجز هذا الشهر ({{ config('currency.symbol', 'دج') }})</div>
            </div>
        </div>
    </div>
</div>

<div class="db-card mb-4">
    <div class="db-card-header">حجوزات آخر 12 شهراً</div>
    <div class="db-card-body">
        <canvas id="pkgChart" height="120"></canvas>
    </div>
</div>

<div class="db-filter-bar mb-3">
    <form method="get" action="{{ route('admin.package-stats') }}" class="flex items-center gap-2 flex flex-wrap items-center gap-2">
        <label class="mb-0 font-weight-bold">فترة الجداول:</label>
        <select name="period" class="db-input" onchange="this.form.submit()">
            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>آخر شهر (من أول الشهر الحالي)</option>
            <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>آخر 3 أشهر</option>
            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>آخر 12 شهراً</option>
        </select>
    </form>
</div>

<div class="grid grid-cols-12 gap-4">
    <div class="col-span-12 lg:col-span-6 mb-4">
        <div class="db-card h-100">
            <div class="db-card-header">أكثر الباقات حجزاً</div>
            <div class="db-card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table db-table mb-0">
                        <thead>
                            <tr>
                                <th>الباقة</th>
                                <th>الخدمة</th>
                                <th>الحجوزات</th>
                                <th>الإيرادات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPackagesForTable as $row)
                                <tr>
                                    <td>{{ $row['package'] }}</td>
                                    <td>{{ $row['service'] }}</td>
                                    <td>{{ number_format($row['count']) }}</td>
                                    <td>{{ currency($row['revenue']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">لا توجد بيانات للفترة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 mb-4">
        <div class="db-card h-100">
            <div class="db-card-header">أكثر الإضافات اختياراً</div>
            <div class="db-card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table db-table mb-0">
                        <thead>
                            <tr>
                                <th>الإضافة</th>
                                <th>مرات الاختيار</th>
                                <th>إيرادات مُقدَّرة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAddons as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ number_format($row['count']) }}</td>
                                    <td>{{ currency($row['revenue']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">لا توجد إضافات في الفترة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('pkgChart');
    if (!ctx || typeof Chart === 'undefined') return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'حجوزات بباقة',
                data: @json($chartData),
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,.12)',
                fill: true,
                tension: 0.25,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
});
</script>
@endsection
