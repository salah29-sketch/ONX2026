@extends('client.layout')

@section('title', 'رسائلي - بوابة العملاء')

@push('styles')
<style>
.messages-compose {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.messages-compose h3 { font-size: 15px; font-weight: 900; color: #1f2937; }
.messages-compose p  { font-size: 12px; color: #6b7280; margin-top: 4px; }

.msg-input {
    width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #f9fafb;
    padding: 12px 16px;
    font-family: inherit;
    font-size: 14px;
    color: #1f2937;
    transition: border-color .2s;
    resize: vertical;
}
.msg-input:focus { outline: none; border-color: #fbbf24; background: #fff; }

.msg-send-btn {
    background: #f59e0b;
    color: #fff;
    border: none;
    padding: 10px 24px;
    border-radius: 999px;
    font-weight: 900;
    font-family: inherit;
    font-size: 14px;
    cursor: pointer;
    transition: background .2s;
}
.msg-send-btn:hover { background: #d97706; }

/* Message bubbles */
.msg-bubble-wrap {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.msg-bubble-item {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}
.msg-bubble-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: #fef3c7; border: 1px solid #fde68a;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.msg-bubble-content {
    flex: 1;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 4px 16px 16px 16px;
    padding: 12px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.msg-bubble-subject { font-size: 13px; font-weight: 800; color: #1f2937; margin-bottom: 4px; }
.msg-bubble-text    { font-size: 13px; color: #374151; line-height: 1.7; }
.msg-bubble-time    { font-size: 11px; color: #9ca3af; margin-top: 8px; }
.msg-bubble-reply .msg-bubble-content { border-color: #fde68a; background: #fffbeb; }
.msg-bubble-reply .msg-bubble-avatar { background: #fef3c7; border-color: #f59e0b; }

.empty-messages {
    text-align: center; padding: 48px 24px;
    border-radius: 20px; border: 1px solid #e5e7eb;
    background: #fff; color: #6b7280;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* Dark mode */
.client-portal-dark .messages-compose { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .messages-compose h3 { color: #fff !important; }
.client-portal-dark .msg-input { background: rgba(255,255,255,.05) !important; border-color: rgba(255,255,255,.1) !important; color: #fff !important; }
.client-portal-dark .msg-input:focus { border-color: #f59e0b !important; background: rgba(255,255,255,.07) !important; }
.client-portal-dark .msg-bubble-avatar  { background: rgba(245,166,35,.15) !important; border-color: rgba(245,166,35,.25) !important; }
.client-portal-dark .msg-bubble-content { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .msg-bubble-subject { color: #fff !important; }
.client-portal-dark .msg-bubble-text    { color: rgba(255,255,255,.75) !important; }
.client-portal-dark .msg-bubble-time    { color: rgba(255,255,255,.3) !important; }
.client-portal-dark .msg-bubble-reply .msg-bubble-content { background: rgba(245,158,11,.12) !important; border-color: rgba(245,158,11,.35) !important; }
.client-portal-dark .msg-bubble-reply .msg-bubble-avatar { background: rgba(245,158,11,.2) !important; border-color: rgba(245,158,11,.4) !important; }
.client-portal-dark .empty-messages     { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
</style>
@endpush

@section('client_content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">✉️ رسائلي</h1>
    <p class="mt-1 text-sm text-gray-500">تواصل مع الفريق — نرد عادةً خلال 24 ساعة</p>
</div>

{{-- نموذج إرسال رسالة --}}
<div class="messages-compose">
    <h3>إرسال رسالة جديدة</h3>
    <p>الفريق يرد عادةً خلال 24 ساعة</p>
    <form method="POST" action="{{ route('client.messages.store') }}" class="mt-4 space-y-3">
        @csrf
        <input
            type="text"
            name="subject"
            value="{{ old('subject') }}"
            placeholder="الموضوع (اختياري)"
            class="msg-input"
            style="resize: none;"
        >
        <textarea
            name="message"
            rows="4"
            required
            placeholder="اكتب رسالتك هنا..."
            class="msg-input"
        >{{ old('message') }}</textarea>
        <button type="submit" class="msg-send-btn">إرسال الرسالة</button>
    </form>
</div>

{{-- قائمة الرسائل --}}
@if($messages->isNotEmpty())
<p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">الرسائل السابقة</p>
<div class="msg-bubble-wrap">
    @foreach($messages as $m)
    <div class="msg-bubble-item">
        <div class="msg-bubble-avatar">👤</div>
        <div class="msg-bubble-content">
            @if($m->subject)
                <p class="msg-bubble-subject">{{ $m->subject }}</p>
            @endif
            <p class="msg-bubble-text">{{ $m->message }}</p>
            <p class="msg-bubble-time">{{ $m->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    @if($m->admin_reply)
    <div class="msg-bubble-item msg-bubble-reply">
        <div class="msg-bubble-avatar">🏢</div>
        <div class="msg-bubble-content msg-bubble-reply-content">
            <p class="msg-bubble-subject">رد الفريق</p>
            <p class="msg-bubble-text">{!! nl2br(e($m->admin_reply)) !!}</p>
            <p class="msg-bubble-time">{{ $m->admin_replied_at?->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    @endif
    @endforeach
</div>
<div class="mt-6">{{ $messages->links() }}</div>
@else
<div class="empty-messages">
    <div style="font-size:40px;margin-bottom:12px;opacity:.5">✉️</div>
    <p class="font-bold text-gray-700">لا توجد رسائل بعد</p>
    <p class="mt-2 text-sm text-gray-500">أرسل أول رسالة للفريق من النموذج أعلاه.</p>
</div>
@endif

@endsection
