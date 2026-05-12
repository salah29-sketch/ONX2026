@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">إضافة باقة جديدة</h1>
        <div class="db-page-subtitle">إنشاء باقة جديدة وتخصيص تفاصيلها وربطها بخدمة.</div>
    </div>
    <a href="{{ route('admin.packages.index') }}" class="px-3 py-1.5 text-sm font-bold rounded-lg bg-[var(--body-bg)] border border-[var(--card-border)] text-[var(--tx-secondary)] hover:bg-[var(--onx-orange-soft)] transition">عودة للقائمة</a>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>الخدمة المرتبطة <span class="text-red-600">*</span></label>
                    <select name="service_id" class="db-input @error('service_id') is-invalid @enderror" required>
                        <option value="">-- اختر الخدمة --</option>
                        @foreach($services as $id => $name)
                            <option value="{{ $id }}" {{ old('service_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>اسم الباقة <span class="text-red-600">*</span></label>
                    <input type="text" name="name" class="db-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="مثال: الباقة الفضية">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>العنوان الفرعي (Subtitle)</label>
                    <input type="text" name="subtitle" class="db-input @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}" placeholder="مثال: التصوير الأساسي">
                    @error('subtitle') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>مدة التنفيذ (Duration)</label>
                    <input type="text" name="duration" class="db-input @error('duration') is-invalid @enderror" value="{{ old('duration') }}" placeholder="مثال: ساعتين">
                    @error('duration') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-4 mb-3">
                    <label>السعر (د.ج) <span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="price" class="db-input @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" required>
                    @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-4 mb-3">
                    <label>السعر القديم (د.ج)</label>
                    <input type="number" step="0.01" name="old_price" class="db-input @error('old_price') is-invalid @enderror" value="{{ old('old_price') }}">
                    @error('old_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-4 mb-3">
                    <label>ملاحظة السعر</label>
                    <input type="text" name="price_note" class="db-input @error('price_note') is-invalid @enderror" value="{{ old('price_note') }}" placeholder="مثال: تدفع لمرة واحدة">
                    @error('price_note') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-3">
                    <label>الوصف</label>
                    <textarea name="description" rows="3" class="db-input @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-3">
                    <label>المميزات</label>
                    <div id="features-list" class="space-y-2 mb-2"></div>
                    <button type="button" onclick="addFeature()"
                        class="text-sm px-3 py-1.5 rounded-lg border border-dashed border-orange-500/40 text-orange-400 hover:bg-orange-500/10 transition">
                        + إضافة ميزة
                    </button>
                    <input type="hidden" name="features" id="features-hidden">
                    @error('features') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-3 mb-3">
                    <label>الترتيب <span class="text-red-600">*</span></label>
                    <input type="number" name="sort_order" class="db-input @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" required>
                    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-9 mb-3 flex items-center gap-4 mt-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" class="rounded border-gray-300" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="isActive">باقة مفعلة</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" class="rounded border-gray-300" id="isFeatured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="isFeatured">باقة مميزة</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_buildable" class="rounded border-gray-300" id="isBuildable" value="1" {{ old('is_buildable') ? 'checked' : '' }}>
                        <label for="isBuildable">باقة قابلة للبناء (Custom)</label>
                    </div>
                </div>
            </div>

            <hr>
            <div class="flex justify-end">
                <button type="submit" class="db-btn-primary">حفظ الباقة</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addFeature(value = '') {
    const list = document.getElementById('features-list');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 feature-row';
    row.innerHTML = `
        <span class="text-green-400 font-bold text-lg">✓</span>
        <input type="text" class="db-input feature-input flex-1" placeholder="أدخل ميزة..." value="${value}">
        <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300 px-2 text-lg">✕</button>
    `;
    list.appendChild(row);
}

function removeFeature(btn) {
    btn.closest('.feature-row').remove();
}

document.querySelector('form').addEventListener('submit', function() {
    const inputs = document.querySelectorAll('.feature-input');
    const features = Array.from(inputs)
        .map(i => i.value.trim())
        .filter(v => v !== '');
    document.getElementById('features-hidden').value = JSON.stringify(features);
});

const existingFeatures = @json(old('features_array', []));
if (Array.isArray(existingFeatures) && existingFeatures.length > 0) {
    existingFeatures.forEach(f => addFeature(f));
} else {
    addFeature();
}
</script>
@endpush