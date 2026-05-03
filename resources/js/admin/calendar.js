/**
 * FullCalendar v6 — تقويم الحجوزات
 * يُصدَّر كـ global window.FullCalendar للتوافق مع السكريبت الموجود في calendar.blade.php
 */
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';

// نجعله متاحاً بشكل global تماماً مثل CDN
window.FullCalendar = {
    Calendar: function (el, options) {
        return new Calendar(el, {
            plugins: [dayGridPlugin, listPlugin, interactionPlugin],
            locales: [frLocale],
            ...options,
        });
    },
};
