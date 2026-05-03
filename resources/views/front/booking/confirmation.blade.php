@extends('layouts.front_tailwind')
@section('title', 'confirmation - ONX')
@section('meta_description', 'تأكيد الحجز في ONX. تم استلام طلبك بنجاح وسيتم مراجعته والتواصل معك في أقرب وقت.')

@push('styles')
<style>
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
    }

    .status-pill.pending {
        background: rgba(245,158,11,.16);
        color: #fbbf24;
        border: 1px solid rgba(245,158,11,.30);
    }

    .status-pill.confirmed {
        background: rgba(34,197,94,.16);
        color: #86efac;
        border: 1px solid rgba(34,197,94,.30);
    }

    .status-pill.neutral {
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.82);
        border: 1px solid rgba(255,255,255,.10);
    }

    .confirm-card {
        border: 1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(16px);
        border-radius: 28px;
    }

    .confirm-item {
        border: 1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.03);
        border-radius: 20px;
        padding: 16px;
    }

    .confirm-item .label {
        display: block;
        margin-bottom: 6px;
        color: rgba(255,255,255,.55);
        font-size: 12px;
        font-weight: 700;
    }

    .confirm-item .value {
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        line-height: 1.7;
    }

    .confirm-divider {
        height: 1px;
        background: rgba(255,255,255,.08);
        margin: 22px 0;
    }

    .confirm-note {
        border: 1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.03);
        border-radius: 20px;
        padding: 16px;
        color: rgba(255,255,255,.82);
        line-height: 1.9;
        font-size: 14px;
    }

    .confirm-success-box {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        border: 1px solid rgba(34,197,94,.25);
        background: rgba(34,197,94,.08);
        border-radius: 22px;
        padding: 16px;
    }

    .confirm-success-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(34,197,94,.18);
        border: 1px solid rgba(34,197,94,.28);
        color: #86efac;
        font-size: 20px;
        font-weight: 900;
    }

    @media (max-width: 768px) {
        .confirm-success-box {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<section class="relative overflow-hidden border-b border-white/10">
    <div class="absolute inset-0">
        <img
            src="{{ asset('img/front/booking/booking-hero.png') }}"
            alt="Confirmation Hero"
            class="h-full w-full object-cover opacity-10"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-[#050505]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6 py-14 lg:px-8 lg:py-16">
        <div class="max-w-3xl">
            <span class="inline-flex rounded-full border border-emerald-500/25 bg-emerald-500/10 px-4 py-1 text-[11px] font-extrabold tracking-wide text-emerald-300">
                CONFIRMATION
            </span>

            <h1 class="mt-4 text-3xl font-black leading-tight text-white sm:text-4xl">
                تم استلام طلبك بنجاح
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-7 text-white/65 sm:text-base">
                طلبك وصلنا بنجاح وهو الآن قيد المراجعة. ستجد هنا التفاصيل الأساسية الخاصة بالحجز وخطوات المتابعة القادمة.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('booking') }}"
                   class="inline-flex rounded-full bg-orange-500 px-5 py-2.5 text-sm font-extrabold text-black transition hover:bg-orange-400">
                    حجز جديد
                </a>

                <a class="inline-flex rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-bold text-white/80 transition hover:border-orange-500/40 hover:bg-orange-500/10 hover:text-white"
                   target="_blank"
                   href="https://wa.me/213540573518?text={{ urlencode(
                        'السلام عليكم، أرسلت طلب حجز عبر موقع ONX.' . "\n" .
                        'رقم الطلب: #' . $booking->id . "\n" .
                        'الاسم: ' . $booking->name . "\n" .
                        'نوع الخدمة: ' . ($booking->service?->name ?? '—')
                   ) }}">
                    تواصل عبر WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
        <div class="confirm-card p-5 sm:p-6">
            <div class="confirm-success-box">
                <div class="confirm-success-icon">✓</div>

                <div>
                    <h2 class="text-lg font-black text-white">تم إنشاء الطلب بنجاح</h2>
                    <p class="mt-1 text-sm leading-7 text-white/60">
                        هذا الطلب مسجل مبدئيًا داخل النظام، والتأكيد النهائي يتم بعد مراجعة الإدارة والتواصل معك.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-extrabold text-white">بيانات الطلب</h3>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="confirm-item">
                        <span class="label">رقم الطلب</span>
                        <div class="value">#{{ $booking->id }}</div>
                    </div>

                    <div class="confirm-item">
                        <span class="label">الحالة</span>
                        <div class="value">
                            @if($booking->status === 'confirmed')
                                <span class="status-pill confirmed">مؤكد</span>
                            @elseif($booking->status === 'unconfirmed')
                                <span class="status-pill pending">غير مؤكد</span>
                            @else
                                <span class="status-pill neutral">{{ $booking->status }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="confirm-item">
                        <span class="label">نوع الخدمة</span>
                        <div class="value">{{ $booking->service?->name ?? '—' }}</div>
                    </div>

                    <div class="confirm-item">
                        <span class="label">الباقة</span>
                        <div class="value">{{ $packageName ?: 'لم يتم تحديد باقة' }}</div>
                    </div>
                </div>
            </div>

            <div class="confirm-divider"></div>

            <div>
                <h3 class="text-sm font-extrabold text-white">معلومات العميل</h3>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="confirm-item">
                        <span class="label">الاسم الكامل</span>
                        <div class="value">{{ $booking->name }}</div>
                    </div>

                    <div class="confirm-item">
                        <span class="label">الهاتف</span>
                        <div class="value">{{ $booking->phone }}</div>
                    </div>

                    <div class="confirm-item">
                        <span class="label">البريد الإلكتروني</span>
                        <div class="value">{{ $booking->email ?: 'غير مضاف' }}</div>
                    </div>

                    @if(!empty($booking->business_name))
                        <div class="confirm-item">
                            <span class="label">اسم النشاط التجاري</span>
                            <div class="value">{{ $booking->business_name }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="confirm-divider"></div>

            <div>
                <h3 class="text-sm font-extrabold text-white">تفاصيل الخدمة</h3>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @if($booking->event_date)
                        <div class="confirm-item">
                            <span class="label">تاريخ الحفل</span>
                            <div class="value">{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</div>
                        </div>

                        <div class="confirm-item">
                            <span class="label">مكان الحفل</span>
                            <div class="value">{{ $locationName ?: 'سيتم تأكيده لاحقًا' }}</div>
                        </div>
                    @endif

                    @if(!empty($booking->ads_type))
                        <div class="confirm-item">
                            <span class="label">نوع الإعلان</span>
                            <div class="value">
                                {{ $booking->ads_type === 'monthly' ? 'اشتراك شهري' : ($booking->ads_type === 'custom' ? 'حسب الطلب' : 'غير محدد') }}
                            </div>
                        </div>
                    @endif

                    @if($booking->budget)
                        <div class="confirm-item">
                            <span class="label">الميزانية</span>
                            <div class="value">{{ number_format((float) $booking->budget) . ' DA' }}</div>
                        </div>
                    @endif

                    @if($booking->deadline)
                        <div class="confirm-item">
                            <span class="label">موعد التسليم</span>
                            <div class="value">{{ $booking->deadline }}</div>
                        </div>
                    @endif
                </div>
            </div>

            @if(!empty($booking->notes))
                <div class="confirm-divider"></div>

                <div>
                    <h3 class="text-sm font-extrabold text-white">الملاحظات</h3>
                    <div class="confirm-note mt-4">
                        {{ $booking->notes }}
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            {{-- ملخص السعر المحسّن — في الـ sidebar --}}
            <div class="confirm-card p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="text-sm font-black text-white">ملخص سريع</h3>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-bold text-white/70">ONX</span>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between border-b border-white/8 pb-3">
                        <span class="text-white/50">رقم الطلب</span>
                        <span class="font-bold text-white">#{{ $booking->id }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/8 pb-3">
                        <span class="text-white/50">الخدمة</span>
                        <span class="font-bold text-white">{{ $booking->service?->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/8 pb-3">
                        <span class="text-white/50">الباقة</span>
                        <span class="font-bold text-white">{{ $packageName ?: '—' }}</span>
                    </div>

                    {{-- السعر الأساسي --}}
                    @if(!empty($packagePrice))
                    <div class="flex items-center justify-between border-b border-white/8 pb-3">
                        <span class="text-white/50">سعر الباقة</span>
                        <span class="font-bold text-white">{{ number_format((float)$packagePrice) }} دج</span>
                    </div>
                    @endif

                    {{-- الإضافات --}}
                    @if(!empty($extraPrice) && $extraPrice > 0)
                    <div class="flex items-center justify-between border-b border-white/8 pb-3">
                        <span class="text-white/50">إضافات</span>
                        <span class="font-bold text-green-400">+{{ number_format((float)$extraPrice) }} دج</span>
                    </div>
                    @endif

                    {{-- الإجمالي --}}
                    @if(!empty($totalPrice) && $totalPrice > 0)
                    <div class="flex items-center justify-between border-b border-white/8 pb-3">
                        <span class="text-white font-black">الإجمالي</span>
                        <span class="font-black text-orange-400 text-base">{{ number_format((float)$totalPrice) }} دج</span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-white/50">الحالة</span>
                        <span class="font-bold text-white">
                            @if($booking->status === 'unconfirmed' || $booking->status === 'pending')
                                <span class="status-pill pending">غير مؤكد</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="status-pill confirmed">مؤكد</span>
                            @else
                                <span class="status-pill neutral">{{ $booking->status }}</span>
                            @endif
                        </span>
                    </div>
                </div>

                @if(!empty($totalPrice) && $totalPrice > 0)
                <p class="mt-3 text-[10px] text-white/35 text-center">* السعر يُؤكد نهائياً بعد التواصل معك</p>
                @endif
            </div>

            <div class="confirm-card p-5">
                <h3 class="mb-4 text-sm font-black text-white">ماذا يحدث الآن؟</h3>

                <div class="space-y-4 text-sm leading-7">
                    <div>
                        <div class="font-bold text-white">1) تم تسجيل الطلب</div>
                        <div class="text-white/55">تم حفظ طلبك داخل النظام بحالة غير مؤكد.</div>
                    </div>

                    <div>
                        <div class="font-bold text-white">2) تتم المراجعة</div>
                        <div class="text-white/55">سيتم التحقق من التفاصيل ومراجعة الطلب من طرف الإدارة.</div>
                    </div>

                    <div>
                        <div class="font-bold text-white">3) التواصل والتأكيد</div>
                        <div class="text-white/55">بعد مراجعة الطلب يتم التواصل معك لتأكيد الحجز أو استكمال الخطوات التالية.</div>
                    </div>
                </div>

                <div class="mt-6 grid gap-2">
                    <a href="{{ route('booking.pdf', $booking->id) }}"
                       class="inline-flex items-center justify-center rounded-full bg-orange-500 px-5 py-3 text-sm font-black text-black transition hover:bg-orange-400">
                        تحميل PDF
                    </a>

                    <a href="{{ route('booking') }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white/80 transition hover:border-orange-500/40 hover:bg-orange-500/10 hover:text-white">
                        العودة إلى صفحة الحجز
                    </a>

                    <a href="https://wa.me/213540573518?text={{ urlencode(
                        'السلام عليكم، هذا رقم طلبي في ONX: #' . $booking->id
                    ) }}"
                       target="_blank"
                       class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white/80 transition hover:border-orange-500/40 hover:bg-orange-500/10 hover:text-white">
                        إرسال رقم الطلب عبر WhatsApp
                    </a>
                </div>
            </div>

            <div class="confirm-card p-5 border-orange-500/20 bg-orange-500/5">
                <h3 class="mb-3 text-sm font-black text-white">منطقة العملاء — بيانات الدخول</h3>
                <p class="text-sm leading-7 text-white/70">
                    النظام منحك حساباً لمتابعة طلبك: مشاهدة تفاصيل الحجز، تحميل ومشاهدة صور المشروع، اختيار حتى 200 صورة مميزة للطباعة، والفيديو النهائي. احفظ البيانات أدناه؛ لن تُعرض مرة أخرى.
                </p>
                @if(!empty($clientLogin))
                    <div class="mt-4 rounded-2xl border border-orange-500/30 bg-black/30 p-4 space-y-2">
                        <div class="flex justify-between items-center gap-2">
                            <span class="text-white/60 text-sm">اسم المستخدم (البريد أو الهاتف):</span>
                            <strong class="text-orange-300 font-mono">{{ $clientLogin }}</strong>
                        </div>
                        <div class="flex justify-between items-center gap-2">
                            <span class="text-white/60 text-sm">كلمة المرور:</span>
                            <strong class="text-orange-300 font-mono">{{ !empty($clientPassword) ? $clientPassword : '— (تواصل معنا إن نسيتها)' }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('client.login') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-full bg-orange-500 px-5 py-3 text-sm font-black text-black hover:bg-orange-400">دخول منطقة العملاء</a>
                @else
                    <p class="mt-2 text-xs text-white/60">لديك حساب مسبقاً. سجّل الدخول من الرابط أدناه.</p>
                    <a href="{{ route('client.login') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-full bg-orange-500 px-5 py-3 text-sm font-black text-black hover:bg-orange-400">دخول منطقة العملاء</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection