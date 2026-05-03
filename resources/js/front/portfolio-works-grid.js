let imageZoomLevel = 1;

function openVideoModal(videoId) {
    const modal = document.getElementById('videoModal');
    const frame = document.getElementById('videoFrame');

    if (!modal || !frame) return;

    frame.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const frame = document.getElementById('videoFrame');

    if (!modal || !frame) return;

    frame.src = '';
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function openImageModal(imageSrc, imageAlt = '') {
    const modal = document.getElementById('imageModal');
    const panel = document.getElementById('imageModalPanel');
    const image = document.getElementById('imageModalSrc');

    if (!modal || !panel || !image) return;

    image.onload = function () {
        imageZoomLevel = 1;
        applyImageZoom();
    };

    image.src = imageSrc;
    image.alt = imageAlt;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    requestAnimationFrame(() => {
        modal.classList.remove('bg-black/0');
        modal.classList.add('bg-black/85');

        panel.classList.remove('opacity-0', 'scale-95');
        panel.classList.add('opacity-100', 'scale-100');
    });

    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    const panel = document.getElementById('imageModalPanel');
    const image = document.getElementById('imageModalSrc');

    if (!modal || !panel || !image) return;

    modal.classList.remove('bg-black/85');
    modal.classList.add('bg-black/0');

    panel.classList.remove('opacity-100', 'scale-100');
    panel.classList.add('opacity-0', 'scale-95');

    setTimeout(() => {
        image.src = '';
        image.alt = '';
        imageZoomLevel = 1;
        applyImageZoom();

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }, 250);
}

function applyImageZoom() {
    const image = document.getElementById('imageModalSrc');
    if (!image) return;

    image.style.transform = `scale(${imageZoomLevel})`;
}

function zoomIn() {
    imageZoomLevel = Math.min(imageZoomLevel + 0.15, 2);
    applyImageZoom();
}

function zoomOut() {
    imageZoomLevel = Math.max(imageZoomLevel - 0.15, 1);
    applyImageZoom();
}

function resetZoom() {
    imageZoomLevel = 1;
    applyImageZoom();
}

function bindPortfolioWorksGrid() {
    document.querySelectorAll('[data-video-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            openVideoModal(btn.dataset.videoId);
        });
    });

    document.querySelectorAll('[data-image-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            openImageModal(btn.dataset.imageSrc, btn.dataset.imageAlt || '');
        });
    });

    document.querySelectorAll('[data-video-close]').forEach((btn) => {
        btn.addEventListener('click', () => {
            closeVideoModal();
        });
    });

    document.querySelectorAll('[data-image-close]').forEach((btn) => {
        btn.addEventListener('click', () => {
            closeImageModal();
        });
    });

    document.querySelectorAll('[data-image-zoom-in]').forEach((btn) => {
        btn.addEventListener('click', () => {
            zoomIn();
        });
    });

    document.querySelectorAll('[data-image-zoom-out]').forEach((btn) => {
        btn.addEventListener('click', () => {
            zoomOut();
        });
    });

    document.querySelectorAll('[data-image-zoom-reset]').forEach((btn) => {
        btn.addEventListener('click', () => {
            resetZoom();
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeVideoModal();
            closeImageModal();
        }
    });

    document.addEventListener('click', (e) => {
        const videoModal = document.getElementById('videoModal');
        const imageModal = document.getElementById('imageModal');

        if (e.target === videoModal) {
            closeVideoModal();
        }

        if (e.target === imageModal) {
            closeImageModal();
        }
    });

    document.addEventListener('contextmenu', (e) => {
        if (e.target.tagName === 'IMG') {
            e.preventDefault();
        }
    });

    document.addEventListener('dragstart', (e) => {
        if (e.target.tagName === 'IMG') {
            e.preventDefault();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    bindPortfolioWorksGrid();
});