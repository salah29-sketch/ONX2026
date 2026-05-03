@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <h1 class="db-page-title">رسالة #{{ $message->id }} — {{ $message->displayName() }}</h1>
    <a href="{{ route('admin.messages.index') }}" class="db-btn-secondary">← الرسائل</a>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-12 gap-4">
    {{-- تفاصيل الرسالة --}}
    <div class="col-span-12 lg:col-span-7">
        <div class="db-card mb-4">
            <div class="db-card-body">
                <div class="mb-3">
                    <div class="text-xs font-bold text-muted mb-1">المرسل</div>
                    <div class="font-weight-bold">{{ $message->displayName() }}</div>
                </div>
                @if($message->phone)
                <div class="mb-3">
                    <div class="text-xs font-bold text-muted mb-1">الهاتف</div>
                    <div>{{ $message->phone }}</div>
                </div>
                @endif
                @if($message->email)
                <div class="mb-3">
                    <div class="text-xs font-bold text-muted mb-1">البريد</div>
                    <div>{{ $message->email }}</div>
                </div>
                @endif
                @if($message->offer_id)
                <div class="mb-3">
                    <div class="text-xs font-bold text-muted mb-1">العرض (سابقاً)</div>
                    <div>مرتبط بالعرض رقم: {{ $message->offer_id }}</div>
                </div>
                @endif
                @if($message->subject)
                <div class="mb-3">
                    <div class="text-xs font-bold text-muted mb-1">الموضوع</div>
                    <div>{{ $message->subject }}</div>
                </div>
                @endif
                <div class="mb-3">
                    <div class="text-xs font-bold text-muted mb-1">الرسالة</div>
                    <div class="p-3 rounded" style="background:rgba(255,255,255,.05); white-space:pre-wrap;">{{ $message->message }}</div>
                </div>
                <div class="text-muted text-sm">
                    {{ $message->created_at->format('Y-m-d H:i') }}
                    @if($message->admin_read_at)
                        — قُرئت {{ $message->admin_read_at->format('Y-m-d H:i') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- الرد --}}
    <div class="col-span-12 lg:col-span-5">
        <div class="db-card mb-4">
            <div class="db-card-body">
                <h5 class="mb-3">الرد على الرسالة</h5>

                @if($message->admin_reply)
                <div class="mb-3 p-3 rounded" style="background:rgba(249,115,22,.08); border:1px solid rgba(249,115,22,.2);">
                    <div class="text-xs font-bold text-muted mb-1">ردك السابق</div>
                    <div style="white-space:pre-wrap;">{{ $message->admin_reply }}</div>
                    @if($message->admin_replied_at)
                    <div class="text-muted text-xs mt-2">{{ $message->admin_replied_at->format('Y-m-d H:i') }}</div>
                    @endif
                </div>
                @endif

                <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="reply" class="db-input" rows="5" placeholder="اكتب ردك هنا..." required>{{ old('reply') }}</textarea>
                        @if($errors->has('reply'))<span class="text-danger">{{ $errors->first('reply') }}</span>@endif
                    </div>
                    <button type="submit" class="db-btn-primary">
                        <i class="fas fa-reply"></i> إرسال الرد
                    </button>
                </form>
            </div>
        </div>

        @if(!$message->admin_read_at)
        <form action="{{ route('admin.messages.mark-read', $message) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="db-btn-secondary w-full">
                <i class="fas fa-check"></i> تحديد كمقروءة
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
