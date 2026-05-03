/**
 * PhotoSwipe — معرض صور البورتفوليو والعميل
 * يُفعَّل على أي حاوية تحمل data-pswp-gallery
 */
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

document.addEventListener('DOMContentLoaded', () => {
    const galleries = document.querySelectorAll('[data-pswp-gallery]');
    galleries.forEach((gallery) => {
        const lightbox = new PhotoSwipeLightbox({
            gallery,
            children: 'a[data-pswp-src]',
            pswpModule: () => import('photoswipe'),
        });
        lightbox.init();
    });
});
