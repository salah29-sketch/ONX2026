@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل الباقة: {{ $package->name }}</h1>
        <div class="db-page-subtitle">تعديل بيانات وتفاصيل الباقة الحالية.</div>
    </div>
    <a href="{{ route('admin.packages.index') }}" class="db-btn-secondary">عودة للقائمة</a>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.packages.update', $package) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>الخدمة المرتبطة <span class="text-danger">*</span></label>
                    <select name="service_id" class=" @error('service_id') is-invalid @enderror" required>
                        <option value="">-- اختر الخدمة --</option>
                        @foreach($services as $id => $name)
                            <option value="{{ $id }}" {{ old('service_id', $package->service_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>اسم الباقة <span class="text-danger">*</span></label>
                    <input type="text" name="name" class=" @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required placeholder="مثال: الباقة الفضية">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>العنوان الفرعي (Subtitle)</label>
                    <input type="text" name="subtitle" class=" @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $package->subtitle) }}">
                    @error('subtitle') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>مدة التنفيذ (Duration)</label>
                    <input type="text" name="duration" class=" @error('duration') is-invalid @enderror" value="{{ old('duration', $package->duration) }}">
                    @error('duration') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-4 mb-4 mb-3">
                    <label>السعر (د.ج) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" class=" @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}" required>
                    @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-4 mb-4 mb-3">
                    <label>السعر القديم (د.ج)</label>
                    <input type="number" step="0.01" name="old_price" class=" @error('old_price') is-invalid @enderror" value="{{ old('old_price', $package->old_price) }}">
                    @error('old_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-4 mb-4 mb-3">
                    <label>ملاحظة السعر</label>
                    <input type="text" name="price_note" class=" @error('price_note') is-invalid @enderror" value="{{ old('price_note', $package->price_note) }}">
                    @error('price_note') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-4 mb-3">
                    <label>الوصف</label>
                    <textarea name="description" rows="3" class=" @error('description') is-invalid @enderror">{{ old('description', $package->description) }}</textarea>
                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-4 mb-3">
                    <label>المميزات</label>
                    <div id="features-list" class="space-y-2 mb-2"></div>
                    <button type="button" onclick="addFeature()"
                        class="text-sm px-3 py-1.5 rounded-lg border border-dashed border-orange-500/40 text-orange-400 hover:bg-orange-500/10 transition">
                        + إضافة ميزة
                    </button>
                    <input type="hidden" name="features" id="features-hidden">
                    @error('features') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-3 mb-4 mb-3">
                    <label>الترتيب <span class="text-danger">*</span></label>
                    <input type="number" name="sort_order" class=" @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $package->sort_order) }}" required>
                    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-9 mb-4 mb-3 flex items-center gap-4 mt-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="isActive">باقة مفعلة</label>
                    </div>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_featured" class="custom-control-input" id="isFeatured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="isFeatured">باقة مميزة</label>
                    </div>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_buildable" class="custom-control-input" id="isBuildable" value="1" {{ old('is_buildable', $package->is_buildable) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="isBuildable">باقة قابلة للبناء (Custom)</label>
                    </div>
                </div>
            </div>

            <hr>
            <div class="flex justify-end">
                <button type="submit" class="db-btn-primary">حفظ التعديلات</button>
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

const existingFeatures = @json(old('features_array', $package->features ?? []));
if (Array.isArray(existingFeatures) && existingFeatures.length > 0) {
    existingFeatures.forEach(f => addFeature(f));
} else {
    addFeature();
}
</script>
@endpush