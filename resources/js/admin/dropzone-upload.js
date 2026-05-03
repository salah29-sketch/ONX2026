/**
 * Dropzone — رفع صور الحجز (admin/bookings/show)
 * يُهيئ Dropzone على أي عنصر يحمل data-dropzone
 */
import Dropzone from 'dropzone';
Dropzone.autoDiscover = false;

document.addEventListener('DOMContentLoaded', () => {
    // ── رفع صور الحجز ──────────────────────────────────────────────
    const photoForm = document.querySelector('[data-dropzone="booking-photos"]');
    if (photoForm) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        new Dropzone(photoForm, {
            url: photoForm.dataset.url,
            paramName: 'photos[]',
            uploadMultiple: true,
            parallelUploads: 5,
            maxFilesize: 10,        // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictDefaultMessage: '<i class="bi bi-cloud-upload fs-2"></i><br>اسحب الصور هنا أو انقر للاختيار',
            dictRemoveFile: 'حذف',
            dictMaxFilesExceeded: 'تجاوزت الحد الأقصى',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            init() {
                this.on('successmultiple', () => {
                    setTimeout(() => window.location.reload(), 800);
                });
                this.on('errormultiple', (files, msg) => {
                    console.error('Dropzone error:', msg);
                });
            },
        });
    }

    // ── رفع ملف الحجز (_payments-files) ───────────────────────────
    const fileForm = document.querySelector('[data-dropzone="booking-file"]');
    if (fileForm) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        new Dropzone(fileForm, {
            url: fileForm.dataset.url,
            paramName: 'file',
            uploadMultiple: false,
            maxFilesize: 20,
            acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx,.zip,.png,.jpg,.jpeg',
            addRemoveLinks: false,
            dictDefaultMessage: '<i class="bi bi-file-earmark-arrow-up fs-2"></i><br>اسحب الملف هنا أو انقر للاختيار',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            init() {
                this.on('success', () => {
                    setTimeout(() => window.location.reload(), 800);
                });
            },
        });
    }
});
