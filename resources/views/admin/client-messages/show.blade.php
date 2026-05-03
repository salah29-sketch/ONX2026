@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">رسالة من العميل</h1>
        <div class="db-page-subtitle">
            @if($message->client)
                <a href="{{ route('admin.clients.show', $message->client->id) }}">{{ $message->client->name }}</a>
                · {{ $message->created_at->format('Y-m-d H:i') }}
            @else
                {{ $message->created_at->format('Y-m-d H:i') }}
            @endif
        </div>
    </div>
    <a href="{{ route('admin.client-messages.index') }}" class="db-btn-secondary">
        <i class="fas fa-arrow-right"></i>
        العودة للقائمة
    </a>
</div>

@if(session('message'))
    <div class="alert-success db-alert">{{ session('message') }}</div>
@endif

<div class="db-card mb-4">
    <div class="db-card-body">
        @if($message->subject)
            <p class="mb-2"><strong>الموضوع:</strong> {{ $message->subject }}</p>
        @endif
        <div class="border rounded p-3 bg-light mb-0">{{ nl2br(e($message->message)) }}</div>
    </div>
</div>

@if($message->admin_reply)
<div class="db-card mb-4">
    <div class="db-card-header">
        <i class="fas fa-check-circle me-2 text-success"></i>
        تم الرد — {{ $message->admin_replied_at?->format('Y-m-d H:i') }}
    </div>
    <div class="db-card-body">
        <div class="border rounded p-3 bg-light mb-0">{{ nl2br(e($message->admin_reply)) }}</div>
    </div>
</div>
@endif

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-reply me-2"></i>
        {{ $message->admin_reply ? 'تعديل الرد أو كتابة رد جديد' : 'كتابة رد (قوالب سريعة)' }}
    </div>
    <div class="db-card-body">
        <form method="POST" action="{{ route('admin.client-messages.reply', $message) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">إدراج قالب</label>
                <select id="replyTemplate" class="form-select" type="button">
                    <option value="">— اختر قالباً —</option>
                    <option value="تم استلام رسالتك، سنرد عليك بالتفاصيل قريباً. شكراً لتواصلك معنا.">تم استلام الرسالة / سنرد قريباً</option>
                    <option value="نحتاج لمزيد من التفاصيل حول طلبك. يرجى توضيح [الموضوع] وسنكمل المعالجة.">نحتاج لمزيد من التفاصيل</option>
                    <option value="تمت معالجة طلبك. في حال وجود أي استفسار نحن هنا لمساعدتك.">تمت المعالجة</option>
                    <option value="نشكرك على تواصلك. نؤكد استلام رسالتك وسيتم الرد خلال 24 ساعة عمل.">تأكيد الاستلام + 24 ساعة</option>
                    <option value="نعتذر عن التأخير. نعمل على طلبك وسنخبرك فور الانتهاء.">اعتذار عن التأخير</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">نص الرد <span class="text-danger">*</span></label>
                <textarea name="reply" id="replyBody" class=" @error('reply') is-invalid @enderror" rows="6" placeholder="اكتب ردك هنا أو اختر قالباً أعلاه..." required>{{ old('reply', $message->admin_reply) }}</textarea>
                @error('reply')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="db-btn-primary">
                <i class="fas fa-paper-plane me-1"></i> حفظ الرد وإظهاره للعميل
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('replyTemplate').addEventListener('change', function() {
    var body = document.getElementById('replyBody');
    var v = this.value;
    if (v) {
        body.value = body.value ? body.value + '\n\n' + v : v;
        this.selectedIndex = 0;
    }
});
</script>
@endsection
