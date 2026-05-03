@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <h1 class="db-page-title">إضافة تصنيف</h1>
    <a href="{{ route('admin.categories.index') }}" class="db-btn-secondary">← التصنيفات</a>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label>الاسم *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name') }}" required>
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>Slug (يُولّد تلقائياً)</label>
                    <input type="text" name="slug" class="db-input" value="{{ old('slug') }}" placeholder="يُولّد من الاسم">
                    @error('slug')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>الأيقونة (إيموجي)</label>
                    <input type="text" name="icon" class="db-input" value="{{ old('icon') }}" placeholder="مثال: 🎬">
                </div>
            </div>
            <div class="mb-4">
                <label>لون التصنيف (يظهر في صفحة الخدمات، الشريط، البطاقات)</label>
                <div class="flex items-center gap-3 flex-wrap">
                    <input type="color" id="cat_bg_picker" value="{{ old('bg_color', '#E87C2A') }}" class="db-input" style="width:56px;height:40px;padding:2px;cursor:pointer;">
                    <input type="text" name="bg_color" id="cat_bg_text" class="db-input" value="{{ old('bg_color', '#E87C2A') }}" maxlength="7" placeholder="#E87C2A" style="max-width:120px;direction:ltr;font-family:monospace;">
                </div>
                <div class="flex flex-wrap gap-2 mt-2" id="color-presets">
                    @foreach(['#E87C2A'=>'برتقالي','#3B82F6'=>'أزرق','#8B5CF6'=>'بنفسجي','#10B981'=>'أخضر','#F43F5E'=>'وردي','#F59E0B'=>'ذهبي','#06B6D4'=>'سماوي','#EC4899'=>'فوشي'] as $hex => $label)
                        <button type="button" onclick="document.getElementById('cat_bg_picker').value='{{ $hex }}';document.getElementById('cat_bg_text').value='{{ $hex }}';" class="db-btn-secondary" style="padding:4px 10px;font-size:11px;border:2px solid {{ $hex }}40;display:flex;align-items:center;gap:4px;">
                            <span style="width:14px;height:14px;border-radius:50%;background:{{ $hex }};display:inline-block;"></span>
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                @error('bg_color')<span class="text-danger">{{ $message }}</span>@enderror
                <small class="text-muted">يتحكم في لون التوهج والبلوبات وبطاقات التصنيف في صفحة الخدمات</small>
            </div>
            <div class="mb-4">
                <label>الوصف</label>
                <textarea name="description" class="db-input" rows="2">{{ old('description') }}</textarea>
            </div>
            <div class="mb-4">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="mb-4">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> نشط</label>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
            <a href="{{ route('admin.categories.index') }}" class="db-btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@push('scripts')
<script>
(function () {
    var pick = document.getElementById('cat_bg_picker');
    var txt = document.getElementById('cat_bg_text');
    if (!pick || !txt) return;
    pick.addEventListener('input', function () { txt.value = pick.value; });
    txt.addEventListener('input', function () {
        var v = txt.value.trim();
        if (/^#[0-9A-Fa-f]{6}$/.test(v)) pick.value = v;
    });
})();
</script>
@endpush
@endsection
