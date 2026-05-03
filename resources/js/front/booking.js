/**
 * bookingSteps — Alpine.data component
 * يتزامن مع booking.js عبر custom events.
 */

document.addEventListener('alpine:init', () => {
  window.Alpine.data('bookingSteps', () => ({
    step: 1,

    init() {
      const serviceInput   = document.getElementById('service_id');   // تصحيح: كان service_type
      const packageRadios  = () => document.querySelectorAll('input[name="selected_package"]');
      const eventDateInput = document.getElementById('event_date');
      const nameInput      = document.querySelector('[name="name"]');
      const phoneInput     = document.querySelector('[name="phone"]');

      const update = () => {
        const serviceSelected = serviceInput && serviceInput.value;
        const packageSelected = Array.from(packageRadios()).some(r => r.checked);
        const dateSelected    = eventDateInput && eventDateInput.value;
        const infoFilled      = (nameInput  && nameInput.value.trim()) ||
                                (phoneInput && phoneInput.value.trim());

        if (infoFilled) {
          this.step = 3;
        } else if (dateSelected || (serviceSelected && packageSelected)) {
          this.step = 2;
        } else {
          this.step = 1;
        }

        syncStepBar(this.step);
      };

      // أحداث التغيير الأساسية
      [serviceInput, eventDateInput, nameInput, phoneInput]
        .filter(Boolean)
        .forEach(el => el.addEventListener('change', update));

      if (nameInput)  nameInput.addEventListener('input', update);
      if (phoneInput) phoneInput.addEventListener('input', update);

      // اختيار الباقة
      document.addEventListener('change', (e) => {
        if (e.target && e.target.name === 'selected_package') update();
      });

      // حدث تغيير الخدمة من booking.js
      document.addEventListener('onx:service-changed', (e) => {
        const sid         = String(e.detail?.serviceId ?? '');
        const bookingCard = document.getElementById('bookingCard');
        const mktId       = bookingCard?.dataset?.marketingServiceId ?? '';

        // نوع الحساب: يظهر فقط للإعلانات
        const contactSection = document.getElementById('contactSection');
        if (contactSection) {
          try {
            // Alpine v3: استخدم _x_dataStack أو Alpine.$data
            const alpineData = (typeof Alpine !== 'undefined' && Alpine.$data)
              ? Alpine.$data(contactSection)
              : contactSection._x_dataStack?.[0];
            if (alpineData) {
              alpineData.showCompanyToggle = (sid === mktId);
              if (sid !== mktId) alpineData.isCompany = false;
            }
          } catch (err) { /* Alpine لم يهيئ بعد */ }
        }

        update();
      });

      // حدث اختيار التاريخ
      document.addEventListener('onx:date-selected', update);

      // تهيئة أولية
      update();
    },
  }));
});

// ── شريط التقدم المرئي ──────────────────────────────────
function syncStepBar(step) {
  for (let i = 1; i <= 3; i++) {
    const dot = document.getElementById('stepDot' + i);
    const lbl = document.getElementById('stepLabel' + i);
    if (!dot) continue;

    if (i < step) {
      dot.className = 'flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-black bg-orange-500 text-black transition-all duration-300';
      dot.textContent = '✓';
    } else if (i === step) {
      dot.className = 'flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-black border-2 border-orange-500 bg-orange-500/15 text-orange-400 transition-all duration-300';
      dot.textContent = i;
    } else {
      dot.className = 'flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-black border border-white/20 bg-white/5 text-white/30 transition-all duration-300';
      dot.textContent = i;
    }

    if (lbl) {
      lbl.className = 'text-[9px] font-bold whitespace-nowrap transition-colors duration-300 '
        + (i <= step ? 'text-white/70' : 'text-white/30');
    }
  }

  for (let i = 1; i <= 2; i++) {
    const line = document.getElementById('stepLine' + i);
    if (line) line.style.background = step > i
      ? 'rgb(249,115,22)'
      : 'rgba(255,255,255,.15)';
  }
}