@extends('client.layout')

@section('title', 'ملفاتي - بوابة العملاء')

@push('styles')
<style>
.file-card {
    display: flex; align-items: center; gap: 16px;
    padding: 16px 20px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    text-decoration: none; color: inherit;
    transition: border-color .2s, box-shadow .2s;
    margin-bottom: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.file-card:hover { border-color: #fcd34d; box-shadow: 0 4px 12px rgba(0,0,0,.06); }

.file-card-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; flex-shrink: 0;
}
.file-card-info { flex: 1; min-width: 0; }
.file-card-name {
    font-size: 14px; font-weight: 800; color: #1f2937;
    margin-bottom: 4px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.file-card-meta { font-size: 12px; color: #9ca3af; }

.file-card-dl {
    font-size: 20px; color: #d1d5db; flex-shrink: 0;
    transition: color .2s;
}
.file-card:hover .file-card-dl { color: #f59e0b; }

.file-card-size {
    font-size: 11px; font-weight: 700;
    color: #6b7280;
    background: #f3f4f6;
    border-radius: 999px;
    padding: 3px 10px;
    flex-shrink: 0;
}

.empty-state-portal {
    text-align: center; padding: 48px 24px;
    color: #6b7280; background: #fff;
    border: 1px solid #e5e7eb; border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* Dark mode */
.client-portal-dark .file-card        { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .file-card:hover  { border-color: rgba(245,166,35,.3) !important; }
.client-portal-dark .file-card-name   { color: #fff !important; }
.client-portal-dark .file-card-meta   { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .file-card-size   { background: #1e2736 !important; color: rgba(255,255,255,.5) !important; }
.client-portal-dark .file-card-dl     { color: rgba(255,255,255,.2) !important; }
.client-portal-dark .file-card:hover .file-card-dl { color: #fbbf24 !important; }
.client-portal-dark .empty-state-portal { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
</style>
@endpush

@section('client_content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">📁 ملفاتي</h1>
    <p class="mt-1 text-sm text-gray-500">الفيديو النهائي، PDF، وملفات ZIP المتاحة للتحميل</p>
</div>

@forelse($files as $file)
    <a href="{{ route('client.files.download', $file->id) }}" class="file-card" download>
        <div class="file-card-icon" style="background: {{ $file->typeColor() }}20; color: {{ $file->typeColor() }};">
            <i class="bi {{ $file->typeIcon() }}"></i>
        </div>
        <div class="file-card-info">
            <div class="file-card-name">{{ $file->label }}</div>
            <div class="file-card-meta">
                {{ $file->booking ? 'حجز #' . $file->booking->id : '' }}
                · {{ $file->created_at->format('d/m/Y') }}
            </div>
        </div>
        @if($file->size)
            <span class="file-card-size">{{ $file->humanSize() }}</span>
        @endif
        <i class="bi bi-download file-card-dl"></i>
    </a>
@empty
    <div class="empty-state-portal">
        <div style="font-size:48px;margin-bottom:16px;opacity:.5">📁</div>
        <p class="font-bold text-gray-700">لا توجد ملفات متاحة حالياً</p>
        <p class="mt-2 text-sm text-gray-500">عند رفع الفيديو النهائي أو أي ملف من الفريق، سيظهر هنا.</p>
    </div>
@endforelse
@endsection
