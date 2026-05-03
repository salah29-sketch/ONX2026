@extends('client.layout')

@section('title', 'الاشتراكات - بوابة العملاء')

@push('styles')
<style>
.sub-card {
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    border-inline-start: 4px solid var(--ads-primary, #3b82f6);
    background: #eff6ff;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.sub-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
.sub-plan-name { font-size: 1.1rem; font-weight: 900; color: #1f2937; }
.sub-status { padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; }
.sub-status.active    { background: #dcfce7; color: #166534; }
.sub-status.expired   { background: #fef3c7; color: #b45309; }
.sub-status.cancelled{ background: #fee2e2; color: #b91c1c; }
.sub-meta { display: grid; gap: 10px; margin-bottom: 14px; }
.sub-meta-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
.sub-meta-label { color: #6b7280; font-weight: 700; }
.sub-meta-value { color: #1f2937; font-weight: 700; }
.sub-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; padding-top: 14px; border-top: 1px solid #e5e7eb; }
.sub-btn { padding: 10px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; transition: all .2s; border: 1px solid; }
.sub-btn-renew { background: rgba(34,197,94,.1); border-color: rgba(34,197,94,.3); color: #166534; }
.sub-btn-renew:hover { background: rgba(34,197,94,.18); }
.sub-btn-type { background: rgba(245,158,11,.1); border-color: rgba(245,158,11,.3); color: #b45309; }
.sub-btn-type:hover { background: rgba(245,158,11,.18); }
.empty-subs { text-align: center; padding: 48px 24px; border-radius: 20px; border: 1px solid #e5e7eb; background: #fff; color: #6b7280; }
.empty-subs .icon { font-size: 48px; margin-bottom: 16px; opacity: .6; }
/* Dark */
.client-portal-dark .sub-card { background: rgba(59,130,246,.08) !important; border-color: rgba(255,255,255,.1) !important; border-inline-start-color: var(--ads-primary, #3b82f6) !important; }
.client-portal-dark .sub-plan-name { color: #fff !important; }
.client-portal-dark .sub-meta-value { color: #fff !important; }
.client-portal-dark .sub-meta-label { color: rgba(255,255,255,.5) !important; }
.client-portal-dark .sub-actions { border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .empty-subs { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; color: rgba(255,255,255,.5) !important; }
</style>
@endpush

@section('client_content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">📢 الاشتراكات</h1>
    <p class="mt-1 text-sm text-gray-500">اشتراكاتك الشهرية في باقات الإعلانات</p>
</div>

@if(session('success'))
    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-green-800">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

@forelse($subscriptions as $sub)
    <div class="sub-card">
        <div class="sub-card-head">
            <span class="sub-plan-name">{{ $sub->planName() }}</span>
            <span class="sub-status {{ $sub->status }}">{{ $sub->statusLabel() }}</span>
        </div>
        <div class="sub-meta">
            <div class="sub-meta-row">
                <span class="sub-meta-label">تاريخ البدء</span>
                <span class="sub-meta-value">{{ $sub->start_date->format('d/m/Y') }}</span>
            </div>
            <div class="sub-meta-row">
                <span class="sub-meta-label">موعد التجديد القادم</span>
                <span class="sub-meta-value">{{ $sub->next_billing_date->format('d/m/Y') }}</span>
            </div>
            <div class="sub-meta-row">
                <span class="sub-meta-label">نوع التجديد</span>
                <span class="sub-meta-value">{{ $sub->renewalTypeLabel() }}</span>
            </div>
        </div>
        @if($sub->isRenewable())
            <div class="sub-actions">
                <form action="{{ route('client.subscriptions.renew', $sub) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="sub-btn sub-btn-renew">تجديد يدوي (شهر)</button>
                </form>
                <form action="{{ route('client.subscriptions.renewal-type', $sub) }}" method="POST" class="inline" id="renewal-form-{{ $sub->id }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="renewal_type" value="{{ $sub->renewal_type === 'automatic' ? 'manual' : 'automatic' }}">
                    <button type="submit" class="sub-btn sub-btn-type">
                        {{ $sub->renewal_type === 'automatic' ? 'تحويل ليدوي' : 'تفعيل التجديد التلقائي' }}
                    </button>
                </form>
            </div>
        @endif
    </div>
@empty
    <div class="empty-subs">
        <div class="icon">📢</div>
        <p class="font-bold text-gray-700">لا توجد اشتراكات</p>
        <p class="mt-2 text-sm text-gray-500">عند حجزك باقة إعلان شهرية من صفحة الحجز، ستظهر هنا.</p>
    </div>
@endforelse

<div class="mt-6">{{ $subscriptions->links() }}</div>
@endsection
