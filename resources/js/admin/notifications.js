/**
 * إشعارات الأدمن — polling كل 30 ثانية
 * يعرض toast عند وصول حجز جديد
 */
const POLL_INTERVAL = 30_000; // 30s
const STORAGE_KEY   = 'onx_admin_notif_ts';

function getLastTs() {
    return parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);
}

function setLastTs(ts) {
    localStorage.setItem(STORAGE_KEY, String(ts));
}

function showToast(booking) {
    const container = document.getElementById('notif-container');
    if (!container) return;

    const toast = document.createElement('a');
    toast.href  = booking.url;
    toast.className = [
        'notif-toast flex items-start gap-3 rounded-2xl border border-orange-500/30',
        'bg-[#1a1a1a] px-4 py-3 shadow-xl cursor-pointer transition-all duration-300',
        'hover:border-orange-500/60 animate-slide-in',
    ].join(' ');

    toast.innerHTML = `
        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-500/15 text-orange-400">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </span>
        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-extrabold text-orange-400 uppercase tracking-widest">حجز جديد</p>
            <p class="mt-0.5 truncate text-sm font-bold text-white">${booking.name}</p>
            <p class="text-xs text-white/55">${booking.service} · ${booking.phone}</p>
        </div>
        <button class="notif-close shrink-0 text-white/30 hover:text-white text-lg leading-none" onclick="event.preventDefault();this.closest('.notif-toast').remove()">✕</button>
    `;

    container.appendChild(toast);

    // إزالة تلقائية بعد 8 ثوانٍ
    setTimeout(() => toast.remove(), 8_000);
}

async function pollNotifications() {
    const since = getLastTs();

    try {
        const res  = await fetch(`/admin/api/notifications?since=${since}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!res.ok) return;

        const data = await res.json();

        if (data.bookings?.length) {
            data.bookings.forEach(showToast);
        }

        setLastTs(data.server_ts);
    } catch (_) {
        // صمت عند انقطاع الشبكة
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // إنشاء حاوية الإشعارات
    if (!document.getElementById('notif-container')) {
        const el = document.createElement('div');
        el.id = 'notif-container';
        el.className = 'fixed bottom-5 left-5 z-[9999] flex flex-col gap-2 w-72';
        document.body.appendChild(el);
    }

    // تعيين الـ timestamp الحالي في أول تحميل (لتجنب إشعارات قديمة)
    if (!getLastTs()) setLastTs(Math.floor(Date.now() / 1000));

    // polling
    setInterval(pollNotifications, POLL_INTERVAL);
});
