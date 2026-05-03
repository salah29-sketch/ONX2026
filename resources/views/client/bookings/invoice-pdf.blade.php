<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    background: #fff;
    color: #0c0f14;
    font-size: 13px;
    direction: rtl;
  }

  /* Header */
  .inv-header {
    background: #0c0f14;
    color: #fff;
    padding: 28px 32px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  .inv-brand { font-size: 26px; font-weight: 900; letter-spacing: -1px; }
  .inv-brand span { color: #f5a623; }
  .inv-brand-sub { font-size: 11px; color: rgba(255,255,255,.5); margin-top: 4px; }

  .inv-meta { text-align: left; }
  .inv-title { font-size: 20px; font-weight: 800; color: #f5a623; margin-bottom: 6px; }
  .inv-num { font-size: 12px; color: rgba(255,255,255,.6); }

  /* Status ribbon */
  .inv-ribbon {
    background: #f5a623;
    color: #000;
    text-align: center;
    padding: 6px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .08em;
  }

  /* Body */
  .inv-body { padding: 28px 32px; }

  /* Two-col info */
  .inv-two-col {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
  }

  .inv-col {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
  }

  .inv-col-title {
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e5e7eb;
  }

  .inv-col-row { margin-bottom: 6px; }
  .inv-col-label { font-size: 10px; color: #64748b; }
  .inv-col-val { font-size: 13px; font-weight: 700; color: #0c0f14; }

  /* Steps */
  .inv-progress {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 24px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 20px;
  }

  .inv-step {
    flex: 1;
    text-align: center;
    position: relative;
  }

  .inv-step-circle {
    width: 30px; height: 30px;
    border-radius: 50%;
    margin: 0 auto 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 800;
    border: 2px solid #e5e7eb;
    background: #fff;
    color: #94a3b8;
  }

  .inv-step.done .inv-step-circle {
    background: #f5a623;
    border-color: #f5a623;
    color: #000;
  }

  .inv-step.active .inv-step-circle {
    background: #fff;
    border-color: #f5a623;
    color: #f5a623;
  }

  .inv-step-label { font-size: 10px; color: #64748b; font-weight: 700; }
  .inv-step.done .inv-step-label,
  .inv-step.active .inv-step-label { color: #0c0f14; }

  .inv-step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin-bottom: 20px;
  }
  .inv-step-line.done { background: #f5a623; }

  /* Package table */
  .inv-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .inv-table th {
    background: #0c0f14;
    color: #fff;
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 800;
    text-align: right;
  }

  .inv-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12px;
    color: #374151;
    text-align: right;
  }

  .inv-table tr:last-child td { border-bottom: none; }
  .inv-table tr:nth-child(even) td { background: #f8fafc; }

  /* Payment table */
  .inv-pay-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
  }

  .inv-pay-table th {
    background: #1e2736;
    color: rgba(255,255,255,.8);
    padding: 8px 12px;
    font-size: 10px;
    font-weight: 800;
    text-align: right;
  }

  .inv-pay-table td {
    padding: 9px 12px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12px;
    text-align: right;
  }

  .inv-pay-table tr:nth-child(even) td { background: #f8fafc; }

  /* Totals */
  .inv-totals {
    width: 280px;
    margin-right: auto;
    margin-left: 0;
  }

  .inv-total-row {
    display: flex;
    justify-content: space-between;
    padding: 7px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
  }

  .inv-total-row.final {
    font-size: 16px;
    font-weight: 900;
    border-bottom: none;
    padding-top: 10px;
    color: #0c0f14;
  }

  .inv-total-row.remaining { color: #ef4444; font-weight: 700; }
  .inv-total-row.paid-val { color: #16a34a; font-weight: 700; }

  /* Section title */
  .inv-section-title {
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .1em;
    margin: 20px 0 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e5e7eb;
  }

  /* Footer */
  .inv-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 16px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 10px;
    color: #94a3b8;
  }
</style>
</head>
<body>

{{-- Header --}}
<div class="inv-header">
    <div>
        <div class="inv-brand">ON<span>X</span></div>
        <div class="inv-brand-sub">
            {{ $companySettings?->company_name ?? 'ONX Media' }}<br>
            {{ $companySettings?->phone ?? '' }}
        </div>
    </div>
    <div class="inv-meta">
        <div class="inv-title">فاتورة / Invoice</div>
        <div class="inv-num">
            رقم الحجز: #{{ $booking->id }}<br>
            تاريخ الإصدار: {{ now()->format('d/m/Y') }}
        </div>
    </div>
</div>

{{-- Status ribbon --}}
<div class="inv-ribbon">
    @if($booking->isFullyPaid()) ✅ مسدّد بالكامل
    @elseif($booking->paidAmount() > 0) 🟡 مسدّد جزئياً — {{ $booking->paymentPercent() }}%
    @else ⏳ في انتظار السداد
    @endif
</div>

<div class="inv-body">
    {{-- Client + Booking info --}}
    <div class="inv-two-col">
        <div class="inv-col">
            <div class="inv-col-title">📋 بيانات العميل</div>
            <div class="inv-col-row">
                <div class="inv-col-label">الاسم</div>
                <div class="inv-col-val">{{ $client->name }}</div>
            </div>
            @if($client->phone)
            <div class="inv-col-row">
                <div class="inv-col-label">الهاتف</div>
                <div class="inv-col-val">{{ $client->phone }}</div>
            </div>
            @endif
            @if($client->email)
            <div class="inv-col-row">
                <div class="inv-col-label">البريد</div>
                <div class="inv-col-val">{{ $client->email }}</div>
            </div>
            @endif
        </div>
        <div class="inv-col">
            <div class="inv-col-title">📦 تفاصيل الخدمة</div>
            <div class="inv-col-row">
                <div class="inv-col-label">نوع الخدمة</div>
                <div class="inv-col-val">{{ $booking->service?->name ?? '—' }}</div>
            </div>
            @if($meta['packageName'])
            <div class="inv-col-row">
                <div class="inv-col-label">الباقة</div>
                <div class="inv-col-val">{{ $meta['packageName'] }}</div>
            </div>
            @endif
            @if($booking->event_date)
            <div class="inv-col-row">
                <div class="inv-col-label">تاريخ الفعالية</div>
                <div class="inv-col-val">{{ $booking->event_date->format('d/m/Y') }}</div>
            </div>
            @endif
            @if($meta['locationName'])
            <div class="inv-col-row">
                <div class="inv-col-label">المكان</div>
                <div class="inv-col-val">{{ $meta['locationName'] }}</div>
            </div>
            @endif
            <div class="inv-col-row">
                <div class="inv-col-label">الحالة</div>
                <div class="inv-col-val">{{ $booking->statusLabel() }}</div>
            </div>
        </div>
    </div>

    {{-- Progress --}}
    @php $step = $booking->statusStep(); @endphp
    <div class="inv-progress">
        <div class="inv-step {{ $step >= 1 ? ($step > 1 ? 'done' : 'active') : '' }}">
            <div class="inv-step-circle">{{ $step > 1 ? '✓' : '1' }}</div>
            <div class="inv-step-label">استلام</div>
        </div>
        <div class="inv-step-line {{ $step > 1 ? 'done' : '' }}"></div>
        <div class="inv-step {{ $step >= 2 ? ($step > 2 ? 'done' : 'active') : '' }}">
            <div class="inv-step-circle">{{ $step > 2 ? '✓' : '2' }}</div>
            <div class="inv-step-label">تأكيد</div>
        </div>
        <div class="inv-step-line {{ $step > 2 ? 'done' : '' }}"></div>
        <div class="inv-step {{ $step >= 3 ? ($step > 3 ? 'done' : 'active') : '' }}">
            <div class="inv-step-circle">{{ $step > 3 ? '✓' : '3' }}</div>
            <div class="inv-step-label">تنفيذ</div>
        </div>
        <div class="inv-step-line {{ $step > 3 ? 'done' : '' }}"></div>
        <div class="inv-step {{ $step >= 4 ? 'done' : '' }}">
            <div class="inv-step-circle">{{ $step >= 4 ? '✓' : '4' }}</div>
            <div class="inv-step-label">مكتمل</div>
        </div>
    </div>

    {{-- Payments --}}
    @if($booking->total_price)
    <div class="inv-section-title">سجل المدفوعات</div>

    @if($booking->payments->isNotEmpty())
    <table class="inv-pay-table">
        <thead>
            <tr>
                <th>نوع الدفعة</th>
                <th>طريقة الدفع</th>
                <th>المرجع</th>
                <th>التاريخ</th>
                <th>المبلغ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->payments as $pay)
            <tr>
                <td>{{ $pay->typeLabel() }}</td>
                <td>{{ $pay->methodLabel() }}</td>
                <td>{{ $pay->reference ?? '—' }}</td>
                <td>{{ $pay->paid_at->format('d/m/Y') }}</td>
                <td><strong>{{ number_format($pay->amount, 0) }} DA</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Totals --}}
    <div class="inv-totals">
        <div class="inv-total-row">
            <span>السعر الإجمالي المتفق عليه</span>
            <span>{{ number_format($booking->total_price, 0) }} DA</span>
        </div>
        <div class="inv-total-row paid-val">
            <span>إجمالي المدفوع</span>
            <span>{{ number_format($booking->paidAmount(), 0) }} DA</span>
        </div>
        @if($booking->remainingAmount() > 0)
        <div class="inv-total-row remaining">
            <span>المبلغ المتبقي</span>
            <span>{{ number_format($booking->remainingAmount(), 0) }} DA</span>
        </div>
        @endif
        <div class="inv-total-row final">
            <span>نسبة السداد</span>
            <span>{{ $booking->paymentPercent() }}%</span>
        </div>
    </div>
    @endif

    @if($booking->notes)
    <div class="inv-section-title">ملاحظات</div>
    <p style="font-size:12px;color:#374151;line-height:1.8;">{{ $booking->notes }}</p>
    @endif
</div>

<div class="inv-footer">
    <span>شكراً لثقتكم في ONX</span>
    <span>
        {{ $companySettings?->phone ?? '' }}
        {{ $companySettings?->email ? '· ' . $companySettings->email : '' }}
    </span>
    <span>تم الإصدار: {{ now()->format('d/m/Y H:i') }}</span>
</div>

</body>
</html>
