document.addEventListener('DOMContentLoaded', () => {
    const mediaType = document.getElementById('media_type');
    const imageInput = document.getElementById('image');
    const imageFields = document.querySelectorAll('.media-image');
    const youtubeFields = document.querySelectorAll('.media-youtube');
    const fileName = document.getElementById('image-file-name');
    const preview = document.getElementById('image-preview');
    const previewWrapper = document.getElementById('image-preview-wrapper');

    function toggleMediaFields() {
        if (!mediaType) return;

        const value = mediaType.value;

        imageFields.forEach((el) => {
            el.style.display = value === 'image' ? '' : 'none';
        });

        youtubeFields.forEach((el) => {
            el.style.display = value === 'youtube' ? '' : 'none';
        });
    }

    function previewPortfolioImage(event) {
        const file = event.target.files && event.target.files[0];

        if (!file) {
            if (fileName) fileName.textContent = 'لم يتم اختيار ملف بعد';
            if (preview) preview.src = '';
            if (previewWrapper) previewWrapper.classList.add('d-none');
            return;
        }

        if (fileName) {
            fileName.textContent = file.name;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            if (preview) preview.src = e.target.result;
            if (previewWrapper) previewWrapper.classList.remove('d-none');
        };

        reader.readAsDataURL(file);
    }

    if (mediaType) {
        mediaType.addEventListener('change', toggleMediaFields);
        toggleMediaFields();
    }

    if (imageInput) {
        imageInput.addEventListener('change', previewPortfolioImage);
    }

    initPortfolioCategoryServiceFilter();
});

function initPortfolioCategoryServiceFilter() {
    const root = document.getElementById('portfolio-category-service-root');
    const payloadEl = document.getElementById('portfolio-services-payload');
    if (!root || !payloadEl) return;

    let services = [];
    try {
        services = JSON.parse(payloadEl.textContent.trim() || '[]');
    } catch (e) {
        console.warn('portfolio-services-payload parse error', e);
        return;
    }

    const catSelect = document.getElementById('portfolio_category_filter');
    const svcSelect = document.getElementById('portfolio_service_id');
    if (!catSelect || !svcSelect) return;

    function categoryMatches(s, catIdStr) {
        const want = parseInt(catIdStr, 10);
        if (!Number.isFinite(want)) return false;
        if (s.category_id == null) return false;
        return parseInt(s.category_id, 10) === want;
    }

    function rebuildServiceOptions() {
        const catIdStr = catSelect.value ? String(catSelect.value) : '';
        const previous = svcSelect.value ? String(svcSelect.value) : '';

        svcSelect.innerHTML = '';

        const optNone = document.createElement('option');
        optNone.value = '';
        optNone.textContent = '— بدون ربط —';
        svcSelect.appendChild(optNone);

        if (!catIdStr) {
            svcSelect.value = '';
            return;
        }

        const filtered = services.filter((s) => categoryMatches(s, catIdStr));

        filtered.forEach((s) => {
            const o = document.createElement('option');
            o.value = String(s.id);
            o.textContent = s.name;
            svcSelect.appendChild(o);
        });

        if (filtered.length === 0) {
            const warn = document.createElement('option');
            warn.value = '';
            warn.disabled = true;
            warn.textContent = '— لا خدمات في هذا التصنيف (اربط الخدمة بتصنيف من «الخدمات») —';
            svcSelect.appendChild(warn);
            svcSelect.value = '';
            return;
        }

        if (previous && [...svcSelect.options].some((o) => o.value === previous)) {
            svcSelect.value = previous;
        }
    }

    catSelect.addEventListener('change', rebuildServiceOptions);

    if (catSelect.value) {
        rebuildServiceOptions();
    }
}
