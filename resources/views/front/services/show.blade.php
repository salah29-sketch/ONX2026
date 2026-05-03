@extends('layouts.front_tailwind')
@section('title', $service->name)
@section('has_hero', true)

@section('content')

<div @if($hasCompare) x-data="serviceCompare({{ (int) $compareMax }})" @endif>

    @foreach($sections as $section)
        @include($section['view'], $section['data'])
    @endforeach

</div>

@foreach($overlays as $overlay)
    @include($overlay['view'], $overlay['data'])
@endforeach

@endsection

@push('styles')
<style>
/* ═══ Service Booking Modal ═══ */
#sbOverlay { display:none;position:fixed;inset:0;z-index:9990;background:rgba(0,0,0,.82);align-items:center;justify-content:center;padding:16px; }
#sbOverlay.open { display:flex;animation:sbFadeIn .2s ease; }
@keyframes sbFadeIn { from{opacity:0} to{opacity:1} }
#sbModal { --sb-color:249,115,22;width:100%;max-width:380px;background:#0d0d0d;border:1px solid rgba(var(--sb-color),.28);border-radius:22px;box-shadow:0 30px 80px rgba(0,0,0,.55),0 0 60px rgba(var(--sb-color),.10);overflow:hidden;display:flex;flex-direction:column;max-height:calc(100vh - 32px); }
#sbModal.anim-in { animation:sbModalIn .38s cubic-bezier(.34,1.56,.64,1); }
@keyframes sbModalIn { from{opacity:0;transform:scale(.88) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }
#sbHeader { padding:14px 16px 0;flex-shrink:0; }
#sbHeader .sb-close { position:absolute;top:12px;left:12px;width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.08);border:none;color:rgba(255,255,255,.55);font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.2s; }
#sbHeader .sb-close:hover { background:rgba(255,255,255,.15);color:#fff; }
.sb-dot { width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:900;transition:all .3s ease;flex-shrink:0; }
.sb-dot.done { background:rgb(var(--sb-color));color:#000; }
.sb-dot.current { border:2px solid rgb(var(--sb-color));background:rgba(var(--sb-color),.12);color:rgb(var(--sb-color)); }
.sb-dot.pending { border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.04);color:rgba(255,255,255,.28); }
.sb-line { height:1px;flex:1;background:rgba(255,255,255,.12);transition:background .4s; }
.sb-line.done { background:rgb(var(--sb-color)); }
#sbBody { flex:1;overflow:hidden;position:relative;direction:ltr; }
#sbTrack { display:flex;width:500%;height:100%;direction:ltr;transition:transform .38s cubic-bezier(.25,.46,.45,.94); }
.sb-panel { width:20%;overflow-y:auto;padding:14px 16px;box-sizing:border-box;direction:rtl;scrollbar-width:thin;scrollbar-color:rgba(var(--sb-color),.3) transparent; }
#sbFooter { padding:12px 16px;flex-shrink:0;border-top:1px solid rgba(255,255,255,.08);display:flex;gap:8px;align-items:center; }
.sb-btn-prev { border-radius:999px;border:1px solid rgba(255,255,255,.18);background:transparent;color:rgba(255,255,255,.65);padding:8px 16px;font-size:12px;font-weight:700;cursor:pointer;transition:.2s; }
.sb-btn-prev:hover { border-color:rgba(255,255,255,.4);color:#fff; }
.sb-btn-next { flex:1;border-radius:999px;border:none;background:rgb(var(--sb-color));color:#000;padding:9px;font-size:12px;font-weight:900;cursor:pointer;transition:.2s;box-shadow:0 4px 16px rgba(var(--sb-color),.3); }
.sb-btn-next:hover { opacity:.88; }
.sb-btn-next:disabled { opacity:.4;cursor:not-allowed; }
.sc-widget { direction:rtl; }
.sc-header { display:flex;align-items:center;justify-content:space-between;padding:2px 0 6px;direction:ltr; }
.sc-arrow { width:28px;height:28px;border-radius:50%;background:transparent;border:none;color:rgba(255,255,255,.5);font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.2s; }
.sc-arrow:hover { background:rgba(255,255,255,.08);color:#fff; }
.sc-title { background:transparent;border:none;color:rgba(255,255,255,.92);font-size:13px;font-weight:800;cursor:pointer;padding:4px 10px;border-radius:8px;transition:.2s; }
.sc-title:hover { background:rgba(var(--sb-color),.12);color:rgb(var(--sb-color)); }
.sc-weekdays { display:grid;grid-template-columns:repeat(7,1fr);text-align:center;margin-bottom:2px;direction:ltr; }
.sc-weekdays > div { font-size:10px;font-weight:800;color:rgba(255,255,255,.35);padding:3px 0; }
.sc-grid { display:grid;gap:2px;direction:ltr; }
.sc-days { grid-template-columns:repeat(7,1fr); }
.sc-cells { grid-template-columns:repeat(4,1fr);gap:4px;padding:4px 0; }
.sc-day { aspect-ratio:1;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:rgba(255,255,255,.82);border-radius:50%;cursor:pointer;transition:.18s;position:relative;border:2px solid transparent; }
.sc-day:not(.other):not(.past):not(.selected):hover { background:rgba(var(--sb-color),.12);color:rgb(var(--sb-color)); }
.sc-day.today { border-color:rgba(var(--sb-color),.5);color:rgb(var(--sb-color));font-weight:800; }
.sc-day.selected { background:rgb(var(--sb-color));color:#111;font-weight:800;border-color:rgb(var(--sb-color));box-shadow:0 2px 10px rgba(var(--sb-color),.3); }
.sc-day.past { color:rgba(255,255,255,.15);cursor:default;pointer-events:none; }
.sc-day.booked { color:rgba(239,68,68,.7) !important;cursor:default;pointer-events:none;font-weight:800; }
.sc-day.other { color:rgba(255,255,255,.08);cursor:default;pointer-events:none; }
.sc-cell { padding:10px 4px;text-align:center;font-size:12px;font-weight:700;color:rgba(255,255,255,.78);border-radius:10px;cursor:pointer;transition:.18s; }
.sc-cell:not(.off):not(.other):hover { background:rgba(var(--sb-color),.12);color:rgb(var(--sb-color)); }
.sc-cell.current { background:rgb(var(--sb-color));color:#111;font-weight:800;box-shadow:0 2px 10px rgba(var(--sb-color),.3); }
.sc-cell.other { color:rgba(255,255,255,.15);cursor:default;pointer-events:none; }
.sc-cell.off { color:rgba(255,255,255,.18);cursor:default;pointer-events:none; }
.sb-input { width:100%;border-radius:12px;border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.05);padding:10px 14px;font-size:13px;color:#fff;outline:none;box-sizing:border-box; }
.sb-input:focus { border-color:rgba(var(--sb-color),.55);box-shadow:0 0 0 3px rgba(var(--sb-color),.12); }
.sb-input::placeholder { color:rgba(255,255,255,.28); }
.sb-field-error { border-color:rgba(239,68,68,.55)!important; }
.sb-err { font-size:11px;font-weight:700;color:rgba(239,68,68,.9);margin-top:4px;display:none; }
.sb-err.show { display:block; }
.sb-status { border-radius:12px;padding:10px 14px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:8px; }
.sb-status.available { background:rgba(34,197,94,.10);border:1px solid rgba(34,197,94,.24);color:rgba(255,255,255,.9); }
.sb-status.booked { background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.24);color:rgba(255,255,255,.9); }
.sb-status.pending { background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.24);color:rgba(255,255,255,.9); }
.sb-success-check { animation:sbSuccessPop .5s cubic-bezier(.34,1.56,.64,1) .1s both; }
@keyframes sbSuccessPop { from{transform:scale(0) rotate(-180deg);opacity:0} to{transform:scale(1) rotate(0deg);opacity:1} }
.sb-success-row { animation:sbFadeUp .4s ease both; }
@keyframes sbFadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.sb-pkg-item { display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 12px;border-radius:12px;cursor:pointer;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.03);transition:.18s;margin-bottom:6px; }
.sb-pkg-item:hover { border-color:rgba(var(--sb-color),.4);background:rgba(var(--sb-color),.06); }
.sb-pkg-item.active { border-color:rgba(var(--sb-color),.6);background:rgba(var(--sb-color),.10); }
.sb-pkg-item .pkg-name { font-size:13px;font-weight:800;color:#fff; }
.sb-pkg-item .pkg-price { font-size:12px;font-weight:800;color:rgb(var(--sb-color));white-space:nowrap; }
.sb-pkg-item .pkg-badge { font-size:9px;font-weight:900;color:#1a1a1a;background:linear-gradient(135deg,#D4AF37,#F5D060);padding:2px 6px;border-radius:6px;margin-right:6px; }
</style>
@endpush

@push('scripts')
@if($hasCompare)
<script>
function serviceCompare(maxItems) {
    return {
        compareList: [],
        showModal: false,
        maxItems: maxItems || 3,
        init() {},
        isIn(id) { return this.compareList.some(p => p.id === id); },
        toggle(id, name, price, featured, features) {
            if (this.isIn(id)) { this.compareList = this.compareList.filter(p => p.id !== id); return; }
            if (this.compareList.length >= this.maxItems) { alert('يمكنك مقارنة ' + this.maxItems + ' باقات كحد أقصى'); return; }
            this.compareList.push({ id, name, price, featured, features: features || [] });
        },
        clear() { this.compareList = []; this.showModal = false; },
        get allModalFeatures() {
            const s = new Set();
            this.compareList.forEach(p => (p.features || []).forEach(f => s.add(f)));
            return [...s];
        },
    };
}
</script>
@endif
@endpush
