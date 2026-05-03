<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>حجز #{{ $booking->id }} - ONX</title>
    <style>
        @page { margin: 24px 18px 16px; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 10px; line-height: 1.35; margin: 0; padding: 0; background: #fff; }
        .page { width: 86%; margin: 14px auto 0; }
        .header { border: 1.6px solid #1f1f1f; padding: 12px 14px; margin-bottom: 14px; background: #fff; }
        .section-title { margin: 0 0 7px; font-size: 12px; font-weight: bold; color: #111; padding-bottom: 4px; border-bottom: 1px solid #d65f13; }
        .two-col { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .two-col td { width: 50%; border: none; padding: 0; vertical-align: top; }
        .box { padding: 2px 0 0; min-height: 80px; background: #fff; }
        .box-title { margin: 0 0 8px; font-size: 10.5px; font-weight: bold; color: #111; padding-bottom: 4px; border-bottom: 1px solid #ededed; }
        .item { margin-bottom: 7px; }
        .label { display: block; font-size: 7.4px; color: #6a6a6a; margin-bottom: 2px; }
        .value { display: block; font-size: 10px; font-weight: bold; color: #161616; word-break: break-word; }
        .money-box { border: 1.3px solid #262626; background: #fff; text-align: center; padding: 10px 8px; min-height: 64px; }
        .money-box.total { background: #fff6ef; border-color: #d65f13; }
        .money-label { display: block; font-size: 7.3px; color: #5f5f5f; margin-bottom: 6px; }
        .money-value { font-size: 12px; font-weight: bold; color: #111; }
        .money-box.total .money-value { color: #c9540d; }
        .footer { margin-top: 11px; padding-top: 7px; border-top: 1px solid #d65f13; font-size: 7.5px; color: #4d4d4d; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <p style="margin:0;font-size:18px;font-weight:bold;">ONX — نسخة إدارية</p>
        <p style="margin:4px 0 0;font-size:9px;color:#666;">حجز #{{ $booking->id }} — {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <div class="section">
        <p class="section-title">معلومات الحجز</p>
        <table class="two-col">
            <tr>
                <td>
                    <div class="box">
                        <p class="box-title">العميل</p>
                        <div class="item"><span class="label">الاسم</span><span class="value">{{ $booking->name }}</span></div>
                        <div class="item"><span class="label">الهاتف</span><span class="value">{{ $booking->phone }}</span></div>
                        <div class="item"><span class="label">البريد</span><span class="value">{{ $booking->email ?: '—' }}</span></div>
                    </div>
                </td>
                <td>
                    <div class="box">
                        <p class="box-title">تفاصيل الحجز</p>
                        <div class="item"><span class="label">نوع الخدمة</span><span class="value">{{ $booking->service?->name ?? '—' }}</span></div>
                        <div class="item"><span class="label">الباقة</span><span class="value">{{ $packageName ?? '—' }}</span></div>
                        @if($booking->service?->slug === 'events')
                        <div class="item"><span class="label">التاريخ</span><span class="value">{{ $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') : '—' }}</span></div>
                        <div class="item"><span class="label">المكان</span><span class="value">{{ $locationName ?? '—' }}</span></div>
                        @endif
                        <div class="item"><span class="label">الحالة</span><span class="value">{{ $booking->status ?? '—' }}</span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">المبلغ</p>
        <div class="money-box total" style="max-width:280px;">
            <span class="money-label">السعر الإجمالي</span>
            <div class="money-value">
                @if(!empty($packagePrice))
                    {{ number_format((float) $packagePrice) }} DA
                @else
                    —
                @endif
            </div>
        </div>
    </div>

    @if(!empty($booking->notes))
    <div class="section">
        <p class="section-title">ملاحظات</p>
        <div style="font-size:9px;">{{ $booking->notes }}</div>
    </div>
    @endif

    <div class="footer">
        <strong>ONX EDGE</strong> — نسخة إدارية — حجز #{{ $booking->id }}
    </div>
</div>
</body>
</html>
