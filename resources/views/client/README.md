# عروض منطقة العملاء (Client Portal Views)

قوالب Blade لبوابة العملاء (`/client/*`). التخطيط الموحّد: `client.layout`.

---

## الهيكل الحالي

```
resources/views/client/
├── layout.blade.php              # التخطيط الرئيسي (sidebar + محتوى + bottom nav)
├── dashboard.blade.php            # لوحة التحكم
├── profile.blade.php             # الملف الشخصي
├── payments.blade.php            # المدفوعات
├── messages.blade.php            # الرسائل
├── review-create.blade.php       # إضافة رأي
├── auth/
│   ├── login.blade.php           # تسجيل الدخول
│   └── set-password.blade.php    # تعيين كلمة المرور (أول دخول)
├── bookings/                     # الحجوزات
│   ├── index.blade.php           # قائمة الحجوزات
│   ├── detail.blade.php          # تفاصيل حجز
│   ├── summary.blade.php         # ملخص للطباعة
│   └── invoice-pdf.blade.php     # قالب PDF الفاتورة
├── media/                        # الميديا والملفات
│   ├── index.blade.php           # الصفحة الرئيسية للميديا
│   ├── files.blade.php           # ملفاتي
│   ├── project-photos.blade.php  # صور المشروع
│   └── project-photos-booking.blade.php  # صور حجز معين
└── README.md
```

---

## ربط العروض بالتحكم

| العرض | الدالة في المتحكم |
|-------|-------------------|
| `client.layout` | أساس كل الصفحات عبر `@extends('client.layout')` |
| `client.dashboard` | `DashboardController::dashboard()` |
| `client.profile` | `DashboardController::profile()` |
| `client.payments` | `DashboardController::payments()` |
| `client.messages` | `DashboardController::messages()` |
| `client.review-create` | `DashboardController::createReview()` |
| `client.bookings.index` | `DashboardController::bookings()` |
| `client.bookings.detail` | `DashboardController::bookingDetail()` |
| `client.bookings.summary` | `DashboardController::bookingSummary()` |
| `client.bookings.invoice-pdf` | `DashboardController::invoicePdf()` (Pdf::loadView) |
| `client.media.index` | `DashboardController::media()` |
| `client.media.files` | غير مستخدم مباشرة — route `client.files` يوجّه إلى `client.media` |
| `client.media.project-photos` | `DashboardController::projectPhotos()` |
| `client.media.project-photos-booking` | `DashboardController::projectPhotosBooking()` |
| `client.auth.login` | `AuthController::showLoginForm()` |
| `client.auth.set-password` | `AuthController::showSetPassword()` |
