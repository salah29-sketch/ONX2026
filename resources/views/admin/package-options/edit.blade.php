@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل خيار لباقة: {{ $package->name }}</h1>
        <div class="db-page-subtitle">تعديل الخيار المخصص.</div>
    </div>
    <a href="{{ route('admin.packages.options.index', $package) }}" class="db-btn-secondary">عودة للخيارات</a>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.packages.options.update', [$package, $option]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>تسمية الخيار (Label) <span class="text-danger">*</span></label>
                    <input type="text" name="label" class=" @error('label') is-invalid @enderror" value="{{ old('label', $option->label) }}" required>
                    @error('label') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-3 mb-4 mb-3">
                    <label>نوع الخيار <span class="text-danger">*</span></label>
                    <select name="type" class=" @error('type') is-invalid @enderror" required>
                        <option value="boolean" {{ old('type', $option->type) == 'boolean' ? 'selected' : '' }}>نعم/لا (Boolean)</option>
                        <option value="number" {{ old('type', $option->type) == 'number' ? 'selected' : '' }}>رقمي (Number)</option>
                        <option value="select" {{ old('type', $option->type) == 'select' ? 'selected' : '' }}>قائمة منسدلة (Select)</option>
                    </select>
                    @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-3 mb-4 mb-3">
                    <label>تأثير السعر <span class="text-danger">*</span></label>
                    <select name="price_effect" class=" @error('price_effect') is-invalid @enderror" required>
                        <option value="fixed" {{ old('price_effect', $option->price_effect) == 'fixed' ? 'selected' : '' }}>ثابت (Fixed)</option>
                        <option value="per_unit" {{ old('price_effect', $option->price_effect) == 'per_unit' ? 'selected' : '' }}>لكل وحدة (Per Unit)</option>
                        <option value="free" {{ old('price_effect', $option->price_effect) == 'free' ? 'selected' : '' }}>مجاني (Free)</option>
                    </select>
                    @error('price_effect') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>السعر (د.ج) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" class=" @error('price') is-invalid @enderror" value="{{ old('price', $option->price) }}" required>
                    @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-3 mb-4 mb-3">
                    <label>الحد الأدنى (للنوع الرقمي)</label>
                    <input type="number" name="min" class=" @error('min') is-invalid @enderror" value="{{ old('min', $option->min) }}">
                    @error('min') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-3 mb-4 mb-3">
                    <label>الحد الأقصى (للنوع الرقمي)</label>
                    <input type="number" name="max" class=" @error('max') is-invalid @enderror" value="{{ old('max', $option->max) }}">
                    @error('max') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-4 mb-3">
                    <label>الخيارات (للقائمة المنسدلة JSON)</label>
                    <textarea name="options" rows="3" class=" @error('options') is-invalid @enderror">{{ old('options', $option->options ? json_encode($option->options, JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                    <small class="text-muted">إذا كان النوع Select، أدخل الخيارات هنا بتنسيق JSON.</small>
                    @error('options') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>القيمة الافتراضية</label>
                    <input type="text" name="default_value" class=" @error('default_value') is-invalid @enderror" value="{{ old('default_value', $option->default_value) }}">
                    @error('default_value') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-4 mb-3">
                    <label>الترتيب <span class="text-danger">*</span></label>
                    <input type="number" name="sort_order" class=" @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $option->sort_order) }}" required>
                    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-4 mb-3 flex items-center gap-4 mt-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" value="1" {{ old('is_active', $option->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="isActive">الخيار مفعل</label>
                    </div>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_required" class="custom-control-input" id="isRequired" value="1" {{ old('is_required', $option->is_required) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="isRequired">إلزامي</label>
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
