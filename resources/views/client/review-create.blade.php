@extends('client.layout')

@section('title', 'أضف رأيك - بوابة العملاء')

@push('styles')
<style>
.review-wrap { max-width: 560px; margin: 0 auto; }

.review-header { margin-bottom: 28px; }
.review-header h1 { font-size: 22px; font-weight: 900; color: #1f2937; }
.review-header p  { font-size: 13px; color: #6b7280; margin-top: 6px; line-height: 1.7; }

.review-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 22px;
    padding: 28px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* Stars */
.star-section { margin-bottom: 24px; }
.star-section-label {
    font-size: 12px; font-weight: 700;
    color: #374151; text-transform: uppercase;
    letter-spacing: .04em; margin-bottom: 12px;
    display: block;
}
.stars-wrap {
    display: flex; gap: 8px; align-items: center;
    margin-bottom: 8px;
}
.star-btn {
    background: none; border: none; cursor: pointer;
    padding: 4px; border-radius: 8px;
    transition: transform .15s;
    line-height: 1;
}
.star-btn:hover  { transform: scale(1.15); }
.star-btn:active { transform: scale(0.95); }
.star-btn .star-icon { font-size: 36px; transition: all .15s; display: block; }
.star-rating-text { font-size: 13px; font-weight: 700; color: #b45309; }

/* Form fields */
.form-field { margin-bottom: 20px; }
.form-label {
    display: block; font-size: 12px; font-weight: 700;
    color: #374151; text-transform: uppercase;
    letter-spacing: .04em; margin-bottom: 8px;
}
.form-textarea {
    width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    background: #f9fafb;
    padding: 14px 16px;
    font-family: inherit; font-size: 14px;
    color: #1f2937; line-height: 1.7;
    resize: vertical; min-height: 120px;
    transition: border-color .2s, background .2s;
}
.form-textarea:focus { outline: none; border-color: #fbbf24; background: #fff; }

.char-count { font-size: 11px; color: #9ca3af; text-align: left; margin-top: 6px; }

.btn-submit {
    width: 100%;
    background: #f59e0b; color: #fff;
    border: none; padding: 14px;
    border-radius: 999px; font-weight: 900;
    font-family: inherit; font-size: 15px;
    cursor: pointer; transition: background .2s, transform .15s;
}
.btn-submit:hover  { background: #d97706; }
.btn-submit:active { transform: scale(.99); }

.review-note {
    display: flex; align-items: flex-start; gap: 8px;
    background: #f9fafb; border: 1px solid #e5e7eb;
    border-radius: 14px; padding: 12px 16px;
    margin-top: 20px;
    font-size: 12px; color: #6b7280; line-height: 1.6;
}
.review-note i { color: #9ca3af; margin-top: 1px; flex-shrink: 0; }

/* Dark mode */
.client-portal-dark .review-header h1   { color: #fff !important; }
.client-portal-dark .review-header p    { color: rgba(255,255,255,.5) !important; }
.client-portal-dark .review-card        { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .star-section-label { color: rgba(255,255,255,.6) !important; }
.client-portal-dark .star-rating-text   { color: #fbbf24 !important; }
.client-portal-dark .form-label         { color: rgba(255,255,255,.6) !important; }
.client-portal-dark .form-textarea      { background: rgba(255,255,255,.05) !important; border-color: rgba(255,255,255,.1) !important; color: #fff !important; }
.client-portal-dark .form-textarea:focus{ border-color: #f59e0b !important; background: rgba(255,255,255,.07) !important; }
.client-portal-dark .char-count         { color: rgba(255,255,255,.3) !important; }
.client-portal-dark .review-note        { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; color: rgba(255,255,255,.42) !important; }
</style>
@endpush

@section('client_content')
<div class="review-wrap">

    <div class="review-header">
        <h1>⭐ أضف رأيك</h1>
        <p>شاركنا تجربتك — رأيك يظهر على الموقع بعد مراجعته من الفريق.</p>
    </div>

    <div class="review-card">
        <form method="POST" action="{{ route('client.review.store') }}">
            @csrf

            {{-- النجوم --}}
            <div class="star-section"
                 x-data="{
                     selected: {{ old('rating', 5) }},
                     hovered: 0,
                     labels: ['', 'ضعيف', 'مقبول', 'جيد', 'جيد جداً', 'ممتاز'],
                     current() { return this.labels[this.hovered || this.selected]; }
                 }">
                <input type="hidden" name="rating" :value="selected">
                <span class="star-section-label">تقييمك</span>
                <div class="stars-wrap">
                    <template x-for="s in 5" :key="s">
                        <button
                            type="button"
                            class="star-btn"
                            @click="selected = s"
                            @mouseenter="hovered = s"
                            @mouseleave="hovered = 0"
                            :aria-label="s + ' نجوم'">
                            <span class="star-icon"
                                  x-text="(hovered || selected) >= s ? '★' : '☆'"
                                  :style="(hovered || selected) >= s ? 'color:#f59e0b' : 'color:#d1d5db'">
                            </span>
                        </button>
                    </template>
                </div>
                <div class="star-rating-text" x-text="current()"></div>
            </div>

            {{-- الرأي --}}
            <div class="form-field"
                 x-data="{ content: '{{ old('content') }}', max: 2000 }">
                <label class="form-label">رأيك (مطلوب)</label>
                <textarea
                    name="content"
                    required
                    maxlength="2000"
                    placeholder="اكتب تجربتك معنا بصدق..."
                    class="form-textarea"
                    x-model="content"
                >{{ old('content') }}</textarea>
                <div class="char-count" x-text="content.length + ' / ' + max"></div>
            </div>

            <button type="submit" class="btn-submit">إرسال الرأي</button>
        </form>

        <div class="review-note">
            <i class="bi bi-shield-check"></i>
            <span>رأيك سيتم مراجعته من قِبل الفريق قبل نشره على الموقع. نقدر صدقك وشفافيتك.</span>
        </div>
    </div>

</div>
@endsection
