<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملخص الحجز #{{ $booking->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f5f6f8; color: #1f2937; padding: 24px; direction: rtl; }
        .wrap { max-width: 700px; margin: 0 auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.08); overflow: hidden; }
        .head { background: #1f2937; color: #fff; padding: 24px 28px; }
        .head h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
        .head .sub { font-size: 13px; opacity: .85; }
        .body { padding: 28px; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .row:last-child { border-bottom: 0; }
        .label { font-weight: 700; color: #6b7280; font-size: 13px; }
        .value { font-weight: 600; }
        .delivery { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 14px 18px; margin-top: 20px; font-size: 14px; font-weight: 600; color: #92400e; }
        .no-print { margin-top: 20px; text-align: center; }
        .no-print button { background: #f59e0b; color: #fff; border: 0; padding: 12px 24px; border-radius: 999px; font-weight: 700; cursor: pointer; font-size: 14px; }
        .no-print button:hover { background: #d97706; }
        @media print {
            body { background: #fff; padding: 0; }
            .wrap { box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head">
            <h1>ملخص الحجز #{{ $booking->id }}</h1>
            <p class="sub">{{ $booking->service?->name ?? '—' }} · {{ $booking->created_at->format('Y-m-d') }}</p>
        </div>
        <div class="body">
            <div class="row"><span class="label">العميل</span><span class="value">{{ $booking->name }}</span></div>
            <div class="row"><span class="label">الهاتف</span><span class="value">{{ $booking->phone }}</span></div>
            @if($booking->event_date)
                <div class="row"><span class="label">تاريخ الفعالية</span><span class="value">{{ $booking->event_date?->format('d/m/Y') ?? '—' }}</span></div>
                <div class="row"><span class="label">المكان</span><span class="value">{{ $meta['locationName'] ?? '—' }}</span></div>
            @endif
            @if(!empty($booking->business_name))
                <div class="row"><span class="label">اسم النشاط</span><span class="value">{{ $booking->business_name ?? '—' }}</span></div>
            @endif
            @if($booking->deadline)
                <div class="row"><span class="label">الموعد النهائي</span><span class="value">{{ $booking->deadline?->format('d/m/Y') ?? '—' }}</span></div>
            @endif
            <div class="row"><span class="label">الباقة</span><span class="value">{{ $meta['packageName'] ?? '—' }}</span></div>
            <div class="row"><span class="label">الإجمالي</span><span class="value">{{ $booking->total_price ? number_format($booking->total_price, 0) . ' DA' : '—' }}</span></div>
            <div class="row"><span class="label">الحالة</span><span class="value">{{ $booking->statusLabel() }}</span></div>

            <div class="delivery">
                <strong>معلومة التسليم:</strong> {{ $booking->deliveryInfoText() }}
            </div>
        </div>
    </div>
    <div class="no-print">
        <button type="button" onclick="window.print()">طباعة الملخص</button>
    </div>
    <script>if (window.location.search.indexOf('print=1') !== -1) window.print();</script>
</body>
</html>
