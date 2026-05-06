{{-- smart.blade.php --}}
@extends('layouts.front_tailwind')
@section('title', 'احجز مشروعك | ONX Edge')

@section('content')

@push('styles')
<style>
*,*::before,*::after{box-sizing:border-box;}
[x-cloak]{display:none!important;}
:root{
    --or:#f97316;--or-dim:rgba(249,115,22,.12);--or-glow:rgba(249,115,22,.35);
    --glass:rgba(255,255,255,.03);--border:rgba(255,255,255,.07);
    --muted:rgba(255,255,255,.45);--dim:rgba(255,255,255,.22);
    --r:14px;--rs:10px;
}
.fi{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:var(--rs);padding:11px 14px;font-size:13px;color:#fff;font-family:inherit;outline:none;transition:border-color .2s,box-shadow .2s;-webkit-appearance:none;appearance:none;}
.fi:focus{border-color:var(--or);box-shadow:0 0 0 3px rgba(249,115,22,.12);}
.fi::placeholder{color:var(--dim);}
.fi option{background:#111;}
.fl{display:block;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:7px;}
.gc{background:var(--glass);backdrop-filter:blur(20px);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
.sh{font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--or);margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.sh::after{content:'';flex:1;height:1px;background:linear-gradient(to left,transparent,var(--or-dim));}

/* service cards */
.sc{position:relative;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--rs);padding:18px 12px;text-align:center;cursor:pointer;transition:all .25s;display:block;width:100%;}
.sc:hover{border-color:rgba(249,115,22,.4);background:rgba(249,115,22,.04);}
.sc.on{border-color:var(--or);background:var(--or-dim);box-shadow:0 0 24px var(--or-glow);}

/* [FIX 3] package cards — compact */
.pc{position:relative;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--rs);padding:9px 10px;cursor:pointer;transition:all .25s;text-align:right;display:block;width:100%;}
.pc:hover{border-color:var(--dim);}
.pc.on{border-color:var(--or);background:var(--or-dim);box-shadow:0 0 16px rgba(249,115,22,.2);}

/* calendar */
.cw{background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;}
.cn{width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,.05);border:none;color:rgba(255,255,255,.6);cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center;transition:all .15s;}
.cn:hover{background:rgba(255,255,255,.12);color:#fff;}
.cd{aspect-ratio:1;border-radius:7px;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:center;transition:all .15s;border:none;background:transparent;cursor:pointer;color:rgba(255,255,255,.6);font-family:inherit;}
.cd:hover:not(.past):not(.sel){background:rgba(255,255,255,.08);color:#fff;}
.cd.past{color:rgba(255,255,255,.2);cursor:not-allowed;}
.cd.sel{background:var(--or);color:#fff;box-shadow:0 0 14px var(--or-glow);}
.cd.tod:not(.sel){border:1px solid rgba(249,115,22,.4);color:var(--or);}

/* submit */
.bs{width:100%;background:var(--or);color:#fff;font-weight:800;font-size:15px;padding:15px;border-radius:var(--rs);border:none;cursor:pointer;font-family:inherit;transition:all .2s;box-shadow:0 4px 24px var(--or-glow);display:flex;align-items:center;justify-content:center;gap:8px;}
.bs:hover:not(:disabled){background:#fb923c;box-shadow:0 6px 32px rgba(249,115,22,.5);transform:translateY(-1px);}
.bs:disabled{opacity:.5;cursor:not-allowed;transform:none;}

/* avail */
.av{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:99px;border:1px solid;}

/* two col */
.bgrid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
@media(max-width:860px){.bgrid{grid-template-columns:1fr;}.cleft{order:-1;}}
.cleft{position:sticky;top:20px;}

/* sum rows */
.sr{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;}
.sr:last-child{border:none;}

@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
.fu{animation:fadeUp .35s ease both;}
@keyframes spin{to{transform:rotate(360deg);}}
.spin{animation:spin 1s linear infinite;}
</style>
@endpush

<div x-data="bk()" x-init="init()" dir="rtl" class="min-h-screen pb-32">

    {{-- HERO --}}
    <div class="text-center pt-12 pb-10 px-4">
        <div style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--or);border:1px solid rgba(249,115,22,.3);border-radius:99px;padding:5px 16px;margin-bottom:16px;">
            ONX Edge — الحجز الفوري
        </div>
        <h1 class="font-syne font-black text-4xl md:text-6xl text-white mb-4" style="line-height:1.1;">
            احجز <span style="color:var(--or);">مشروعك</span>
        </h1>
        <p style="color:var(--muted);font-size:13px;max-width:360px;margin:0 auto;">
            اختر الخدمة وأكمل بياناتك — نتواصل معك لتأكيد التفاصيل
        </p>
    </div>

    <div style="max-width:1100px;margin:0 auto;padding:0 16px;">

        {{-- DONE --}}
        <div x-show="step==='done'" x-cloak class="fu" style="max-width:480px;margin:0 auto;">
            <div class="gc" style="padding:40px;text-align:center;">
                <div style="width:72px;height:72px;margin:0 auto 20px;border-radius:50%;border:2px solid #4ade80;background:rgba(74,222,128,.08);display:flex;align-items:center;justify-content:center;font-size:2rem;color:#4ade80;">✓</div>
                <div style="font-family:'Syne',sans-serif;font-size:26px;font-weight:900;color:#fff;margin-bottom:6px;">تم استلام حجزك!</div>
                <div style="display:inline-block;border:1px solid rgba(249,115,22,.3);color:var(--or);font-weight:800;padding:4px 16px;border-radius:99px;margin-bottom:16px;font-size:14px;" x-text="'#'+(done.booking_ref||'')"></div>
                <p style="color:var(--muted);font-size:13px;margin-bottom:20px;line-height:1.7;">سنتواصل معك قريباً لتأكيد التفاصيل ومعلومات العربون.</p>
                <template x-if="done.generated_password">
                    <div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--rs);padding:16px;margin-bottom:20px;text-align:right;">
                        <div style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;">بيانات دخولك — احتفظ بها</div>
                        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:12px;">
                            <span style="color:var(--muted);">البريد</span><span style="color:#fff;font-weight:700;" x-text="form.email"></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12px;">
                            <span style="color:var(--muted);">كلمة المرور</span><span style="color:var(--or);font-weight:800;letter-spacing:.1em;" x-text="done.generated_password"></span>
                        </div>
                        <div style="font-size:10px;color:#f87171;text-align:center;margin-top:8px;">تظهر مرة واحدة فقط</div>
                    </div>
                </template>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a :href="done.confirmation_url" class="bs" style="text-decoration:none;">عرض تفاصيل الحجز ←</a>
                    <a href="/" style="display:block;border:1px solid var(--border);color:var(--muted);text-decoration:none;padding:12px;border-radius:var(--rs);font-size:13px;text-align:center;">العودة للرئيسية</a>
                </div>
            </div>
        </div>

        <div x-show="step==='form'" x-cloak>

            {{-- [FIX 1] STEP 0: اختر الخدمة — التصنيفات تظهر بشكل صحيح --}}
            <div style="margin-bottom:24px;">
                <div class="sh"><span>اختر نوع الخدمة</span></div>

                <div x-show="initLoading" style="text-align:center;padding:40px 0;color:var(--dim);font-size:13px;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg class="spin" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    جاري التحميل...
                </div>

                {{-- [FIX 1] عرض التصنيفات أولاً ثم خدماتها مباشرة --}}
                <div x-show="!initLoading">
                    <template x-for="cat in categories" :key="cat.id">
                        <div x-show="(bycat[cat.id]||[]).length > 0" style="margin-bottom:20px;">
                            {{-- عنوان التصنيف بارز --}}
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                <span x-text="cat.icon" style="font-size:1rem;"></span>
                                <span style="font-size:11px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;color:var(--or);border:1px solid rgba(249,115,22,.3);border-radius:99px;padding:3px 12px;" x-text="cat.name"></span>
                            </div>
                            {{-- خدمات التصنيف --}}
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;">
                                <template x-for="svc in (bycat[cat.id]||[])" :key="svc.id">
                                    <button type="button" @click="pickSvc(svc)" class="sc" :class="sel?.id===svc.id?'on':''">
                                        <div x-show="sel?.id===svc.id" style="position:absolute;top:8px;left:8px;width:18px;height:18px;background:var(--or);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;color:#fff;">✓</div>
                                        <span x-text="svc.icon||cat.icon" style="font-size:2rem;display:block;margin-bottom:8px;"></span>
                                        <div style="font-weight:700;font-size:13px;color:#fff;margin-bottom:4px;" x-text="svc.name"></div>
                                        <div style="font-size:11px;color:var(--muted);line-height:1.4;" x-text="(svc.description||'').substring(0,55)"></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- STEP 1: النموذج الكامل --}}
            <div x-show="sel" class="bgrid fu" id="booking-form-start">

                {{-- COL RIGHT: النموذج --}}
                <div style="display:flex;flex-direction:column;gap:16px;">

                    {{-- [FIX 3] الباقة — كروت مدمجة --}}
                    <div class="gc" style="padding:20px;">
                        <div class="sh"><span>الباقة</span></div>
                        <div x-show="loadPkg" style="text-align:center;padding:20px;color:var(--dim);font-size:12px;">جاري التحميل...</div>
                        <div x-show="!loadPkg" style="display:flex;flex-direction:column;gap:6px;">
                            <template x-for="pkg in pkgs" :key="pkg.id">
                                <button type="button" @click="pickPkg(pkg)" class="pc" :class="curPkg?.id===pkg.id&&!isCustom?'on':''">
                                    <div x-show="pkg.is_featured" style="position:absolute;top:-6px;right:10px;background:var(--or);color:#fff;font-size:9px;font-weight:800;padding:1px 8px;border-radius:99px;">⭐ الأكثر طلباً</div>
                                    <div style="position:absolute;top:8px;left:8px;width:15px;height:15px;border-radius:50%;border:2px solid;display:flex;align-items:center;justify-content:center;font-size:8px;transition:all .2s;"
                                        :style="curPkg?.id===pkg.id&&!isCustom?'border-color:var(--or);background:var(--or);color:#fff;':'border-color:rgba(255,255,255,.2);color:transparent;'">✓</div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <div style="padding-left:24px;">
                                            <div style="font-weight:700;font-size:12px;color:#fff;" x-text="pkg.name"></div>

                                        </div>
                                        <div style="text-align:left;flex-shrink:0;">
                                            <span x-show="pkg.old_price>0&&pkg.price<pkg.old_price" style="font-size:10px;color:var(--dim);text-decoration:line-through;display:block;" x-text="n(pkg.old_price)+' دج'"></span>
                                            <span style="font-size:14px;font-weight:900;color:var(--or);" x-text="pkg.price>0?n(pkg.price)+' دج':(pkg.price_note||'—')"></span>
                                        </div>
                                    </div>

                                </button>
                            </template>

                            <template x-if="pkgs.some(p=>p.is_buildable)">
                                <button type="button" @click="useCustom()" class="pc" :class="isCustom?'on':''" style="border-style:dashed;text-align:center;padding:10px;">
                                    <div style="position:absolute;top:8px;left:8px;width:15px;height:15px;border-radius:50%;border:2px solid;display:flex;align-items:center;justify-content:center;font-size:8px;"
                                        :style="isCustom?'border-color:var(--or);background:var(--or);color:#fff;':'border-color:rgba(255,255,255,.2);color:transparent;'">✓</div>
                                    <div style="font-size:1rem;margin-bottom:2px;">✦</div>
                                    <div style="font-weight:700;font-size:12px;color:#fff;">باقة مخصصة</div>
                                    <div style="font-size:10px;color:var(--muted);margin-top:1px;">اختر الخيارات التي تريدها فقط</div>
                                </button>
                            </template>
                        </div>

                        {{-- custom options --}}
                        <div x-show="isCustom&&custOpts.length" style="margin-top:12px;border-top:1px solid var(--border);padding-top:12px;">
                            <div class="fl">اختر الخيارات</div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <template x-for="opt in custOpts" :key="opt.id">
                                    <button type="button" @click="togOpt(opt)"
                                        style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:var(--rs);border:1px solid;transition:all .15s;background:transparent;cursor:pointer;font-family:inherit;"
                                        :style="opts[opt.id]?'border-color:var(--or);background:var(--or-dim);':'border-color:var(--border);'">
                                        <div style="width:16px;height:16px;border:2px solid;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"
                                            :style="opts[opt.id]?'border-color:var(--or);background:var(--or);':'border-color:rgba(255,255,255,.2);'">
                                            <span x-show="opts[opt.id]" style="color:#fff;font-size:9px;">✓</span>
                                        </div>
                                        <span style="flex-grow:1;text-align:right;font-size:12px;color:#fff;" x-text="opt.name"></span>
                                        <span style="font-size:11px;font-weight:700;color:var(--or);white-space:nowrap;">+<span x-text="n(opt.price)"></span> دج</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- معلومات العميل --}}
                    <div class="gc" style="padding:20px;">
                        <div class="sh"><span>معلوماتك</span></div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div style="grid-column:1/-1;">
                                <label class="fl">الاسم الكامل *</label>
                                <input type="text" x-model="form.name" class="fi" placeholder="اسمك الكامل">
                            </div>
                            <div>
                                <label class="fl">رقم الهاتف *</label>
                                <input type="tel" x-model="form.phone" class="fi" placeholder="0550000000" dir="ltr">
                            </div>
                            <div>
                                <label class="fl">البريد الإلكتروني *</label>
                                <input type="email" x-model="form.email" class="fi" placeholder="email@example.com" dir="ltr">
                            </div>
                            <div style="grid-column:1/-1;">
                                <label class="fl">ملاحظات <span style="color:var(--dim);font-weight:400;text-transform:none;">(اختياري)</span></label>
                                <textarea x-model="form.notes" class="fi" style="min-height:70px;resize:vertical;" placeholder="أي تفاصيل إضافية..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- مكان الفعالية + الوقت في بطاقة واحدة --}}
                    <div class="gc" style="padding:20px;" x-show="(sel?.show_wilaya_selector||sel?.show_venue_selector)||(date&&btype!=='subscription')">
                        <div class="sh"><span>مكان الفعالية والوقت</span></div>

                        {{-- الولاية والقاعة في نفس السطر --}}
                        <div x-show="sel?.show_wilaya_selector||sel?.show_venue_selector">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                                {{-- الولاية --}}
                                <div x-show="sel?.show_wilaya_selector">
                                    <label class="fl">الولاية</label>
                                    <select x-model="form.wilaya_id" @change="debouncedOnWilaya()" class="fi">
                                        <option value="">اختر الولاية...</option>
                                        <template x-for="w in wilayas" :key="w.id">
                                            <option :value="w.id" x-text="w.code+' — '+w.name"></option>
                                        </template>
                                    </select>
                                </div>
                                {{-- القاعة كـ select --}}
                                <div x-show="sel?.show_venue_selector">
                                    <label class="fl">القاعة</label>
                                    <div x-show="!showVenueInput">
                                        <select x-model="form.venue_id" @change="form.venue_custom=''" class="fi">
                                            <option value="">اختر القاعة...</option>
                                            <template x-for="v in venues" :key="v.id">
                                                <option :value="v.id" x-text="v.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div x-show="showVenueInput">
                                        <input type="text" x-model="form.venue_custom" @input="form.venue_id=null" class="fi" placeholder="اكتب اسم القاعة...">
                                    </div>
                                </div>
                            </div>
                            {{-- رسوم التنقل --}}
                            <div x-show="pricing.travel_cost>0" style="margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;font-size:11px;background:rgba(249,115,22,.06);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:6px 12px;">
                                <span style="color:var(--muted);">رسوم التنقل</span>
                                <span style="color:var(--or);font-weight:700;">+<span x-text="n(pricing.travel_cost)"></span> دج</span>
                            </div>
                            {{-- زر لم أجد قاعتي --}}
                            <div x-show="sel?.show_venue_selector" style="margin-bottom:10px;">
                                <button x-show="!showVenueInput" type="button"
                                    @click="showVenueInput=true;form.venue_id=null;"
                                    style="font-size:11px;color:var(--muted);background:transparent;border:1px dashed rgba(255,255,255,.15);border-radius:var(--rs);padding:6px 14px;cursor:pointer;font-family:inherit;transition:all .15s;display:block;width:100%;"
                                    onmouseover="this.style.borderColor='rgba(249,115,22,.4)';this.style.color='var(--or)'"
                                    onmouseout="this.style.borderColor='rgba(255,255,255,.15)';this.style.color='var(--muted)'"
                                >+ لم أجد قاعتي — أضف يدوياً</button>
                                <button x-show="showVenueInput" type="button"
                                    @click="showVenueInput=false;form.venue_custom='';"
                                    style="font-size:11px;color:var(--dim);background:transparent;border:none;cursor:pointer;font-family:inherit;padding:0;">
                                    ← رجوع إلى القائمة
                                </button>
                            </div>
                            {{-- فاصل --}}
                            <div x-show="date&&btype!=='subscription'" style="border-top:1px solid var(--border);margin-bottom:14px;"></div>
                        </div>

                        {{-- الوقت --}}
                        <div x-show="date&&btype!=='subscription'">
                            <div style="font-size:11px;font-weight:700;letter-spacing:.06em;color:var(--muted);margin-bottom:10px;text-transform:uppercase;">الوقت</div>
                            <div x-show="avail==='available'">
                                <template x-if="btype==='appointment'">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="fl">وقت الموعد</label>
                                            <input type="time" x-model="form.slot_start" @change="calcEnd()" class="fi" dir="ltr">
                                        </div>
                                        <div>
                                            <label class="fl">ينتهي</label>
                                            <input type="time" :value="form.slot_end" class="fi" style="opacity:.5;" dir="ltr" readonly>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="btype!=='appointment'">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="fl">وقت البداية</label>
                                            <input type="time" x-model="form.start_time" @change="fetchPrice()" class="fi" dir="ltr">
                                        </div>
                                        <div>
                                            <label class="fl">وقت النهاية</label>
                                            <input type="time" value="04:00" class="fi" style="opacity:.5;cursor:not-allowed;" dir="ltr" readonly>
                                        </div>
                                    </div>
                                </template>
                                {{-- تنبيه دائم --}}
                                <div style="margin-top:8px;display:flex;align-items:center;justify-content:space-between;font-size:11px;background:rgba(249,115,22,.06);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:6px 12px;">
                                    <span style="color:var(--muted);">⚠ ما بعد 04:00 صباحاً تُطبَّق رسوم إضافية</span>
                                    <span x-show="pricing.time_cost>0" style="color:var(--or);font-weight:700;">+<span x-text="n(pricing.time_cost)"></span> دج</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- error --}}
                    <div x-show="err" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 16px;border-radius:var(--rs);font-size:13px;" x-text="err"></div>

                    {{-- submit mobile --}}
                    <div class="md:hidden">
                        <button type="button" @click="go()" :disabled="busy||!ok()" class="bs">
                            <span x-show="!busy">🎉 تأكيد الحجز</span>
                            <span x-show="busy" style="display:flex;align-items:center;gap:8px;"><svg class="spin" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>جاري الإرسال...</span>
                        </button>
                    </div>
                </div>

                {{-- COL LEFT: تقويم + ملخص --}}
                <div class="cleft" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- التقويم --}}
                    <div x-show="btype!=='subscription'" class="cw">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--border);">
                            <button type="button" @click="calNext()" class="cn">←</button>
                            <span style="font-size:13px;font-weight:700;color:#fff;" x-text="calTitle()"></span>
                            <button type="button" @click="calPrev()" class="cn">→</button>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(7,1fr);border-bottom:1px solid var(--border);">
                            <template x-for="d in ['ح','ن','ث','ر','خ','ج','س']" :key="d">
                                <div style="padding:8px 0;text-align:center;font-size:10px;color:var(--dim);font-weight:700;" x-text="d"></div>
                            </template>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(7,1fr);padding:8px;gap:3px;">
                            <template x-for="i in calOff()" :key="'e'+i"><div></div></template>
                            <template x-for="day in calDays()" :key="day.s">
                                <button type="button" class="cd"
                                    :class="{past:day.p,sel:date===day.s,tod:day.t}"
                                    :disabled="day.p"
                                    @click="!day.p&&debouncedPickDate(day.s)"
                                    x-text="day.d">
                                </button>
                            </template>
                        </div>
                        {{-- [FIX 2] حالة التاريخ تظهر هنا، تحت التقويم مباشرة --}}
                        <div x-show="date" style="padding:10px 12px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                            <span style="font-size:11px;color:var(--muted);">📅 <span x-text="fd(date)"></span></span>
                            <span x-show="avail" class="av"
                                :style="avail==='available'?'border-color:rgba(34,197,94,.3);color:#4ade80;background:rgba(34,197,94,.08);':avail==='pending'?'border-color:rgba(234,179,8,.3);color:#facc15;background:rgba(234,179,8,.08);':'border-color:rgba(239,68,68,.3);color:#f87171;background:rgba(239,68,68,.08);'"
                                x-text="avail==='available'?'✓ متاح':avail==='pending'?'⏳ قيد المراجعة':'✗ محجوز'">
                            </span>
                            <div x-show="!avail&&date" style="font-size:10px;color:var(--dim);display:flex;align-items:center;gap:4px;">
                                <svg class="spin" style="width:10px;height:10px;" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                جاري التحقق...
                            </div>
                        </div>
                    </div>

                    {{-- ملخص الحجز --}}
                    <div class="gc" style="padding:20px;">
                        <div class="sh"><span>ملخص الحجز</span></div>

                        <div x-show="!curPkg&&!isCustom" style="text-align:center;padding:20px 0;color:var(--dim);font-size:12px;">
                            اختر باقة لعرض الملخص
                        </div>

                        <div x-show="curPkg||isCustom" style="display:flex;flex-direction:column;">
                            <div class="sr" x-show="sel">
                                <span style="color:var(--muted);">الخدمة</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="sel?.name"></span>
                            </div>
                            <div class="sr" x-show="curPkg">
                                <span style="color:var(--muted);">الباقة</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="curPkg?.name||'مخصصة'"></span>
                            </div>
                            <div class="sr" x-show="date">
                                <span style="color:var(--muted);">التاريخ</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="fd(date)"></span>
                            </div>
                            <div class="sr" x-show="pricing.base>0">
                                <span style="color:var(--muted);">السعر الأساسي</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="n(pricing.base)+' دج'"></span>
                            </div>
                            <div class="sr" x-show="pricing.options_cost>0">
                                <span style="color:var(--muted);">الخيارات</span>
                                <span style="font-weight:700;color:var(--or);font-size:13px;" x-text="'+'+n(pricing.options_cost)+' دج'"></span>
                            </div>
                            <div class="sr" x-show="pricing.time_cost>0">
                                <span style="color:var(--muted);">رسوم الوقت</span>
                                <span style="font-weight:700;color:var(--or);font-size:13px;" x-text="'+'+n(pricing.time_cost)+' دج'"></span>
                            </div>
                            <div class="sr" x-show="pricing.travel_cost>0">
                                <span style="color:var(--muted);">رسوم التنقل</span>
                                <span style="font-weight:700;color:var(--or);font-size:13px;" x-text="'+'+n(pricing.travel_cost)+' دج'"></span>
                            </div>

                            <div x-show="pricing.total>0" style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                                <span style="font-size:13px;font-weight:700;color:#fff;">الإجمالي</span>
                                <span style="font-size:22px;font-weight:900;color:var(--or);font-family:'Syne',sans-serif;" x-text="n(pricing.total)+' دج'"></span>
                            </div>

                            <div x-show="pricing.deposit>0" style="margin-top:10px;background:rgba(249,115,22,.08);border:1px solid rgba(249,115,22,.25);border-radius:var(--rs);padding:10px 12px;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:11px;color:rgba(249,115,22,.8);">العربون المطلوب</span>
                                <span style="font-size:15px;font-weight:800;color:var(--or);" x-text="n(pricing.deposit)+' دج'"></span>
                            </div>

                            {{-- رمز ترويجي داخل الملخص تحت السعر --}}
                            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                                <div style="font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:7px;">رمز ترويجي</div>
                                <div style="display:flex;gap:8px;">
                                    <input type="text" x-model="form.promo_code" class="fi" placeholder="PROMO2026" dir="ltr" style="flex:1;padding:8px 12px;font-size:12px;">
                                    <button type="button" @click="applyPromo()"
                                        style="background:rgba(255,255,255,.06);border:1px solid var(--border);color:#fff;font-weight:700;font-size:12px;padding:0 14px;border-radius:var(--rs);cursor:pointer;white-space:nowrap;font-family:inherit;transition:all .15s;"
                                        x-text="promoOk?'✓ مفعّل':'تطبيق'">
                                    </button>
                                </div>
                                <div x-show="promoMsg" style="margin-top:5px;font-size:11px;" :style="promoOk?'color:#4ade80;':'color:#f87171;'" x-text="promoMsg"></div>
                            </div>

                            {{-- submit desktop --}}
                            <button type="button" @click="go()" :disabled="busy||!ok()" class="bs hidden md:flex" style="margin-top:16px;">
                                <span x-show="!busy">🎉 تأكيد الحجز الآن</span>
                                <span x-show="busy" style="display:flex;align-items:center;gap:8px;"><svg class="spin" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>جاري الإرسال...</span>
                            </button>

                            <p x-show="!ok()&&(curPkg||isCustom)" style="font-size:10px;color:var(--dim);text-align:center;margin-top:8px;">
                                أكمل جميع البيانات المطلوبة للمتابعة
                            </p>
                        </div>
                    </div>

                </div>{{-- cleft --}}
            </div>{{-- bgrid --}}
        </div>{{-- step form --}}
    </div>
</div>

@push('scripts')
<script>
// Debounce utility
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function bk(){return{
    initLoading:true,step:'form',
    categories:[],bycat:{},wilayas:[],venues:[],pkgs:[],custOpts:[],
    sel:null,curPkg:null,isCustom:false,opts:{},date:null,avail:null,
    loadPkg:false,
    showVenueInput:false,
    form:{name:'',email:'',phone:'',notes:'',
        /* [FIX 5] قيم افتراضية للوقت */
        start_time:'19:00',end_time:'04:00',
        slot_start:'',slot_end:'',
        wilaya_id:null,venue_id:null,venue_custom:'',promo_code:''},
    pricing:{base:0,options_cost:0,time_cost:0,travel_cost:0,subtotal:0,total:0,deposit:0},
    promoOk:false,promoMsg:'',
    busy:false,err:'',done:{},
    lastSubmit:0,
    calY:new Date().getFullYear(),calM:new Date().getMonth()+1,

    get btype(){return this.sel?.booking_type??'event';},

    async init(){
        try {
            // Check cache first
            const cached = localStorage.getItem('smartBookingInit');
            if (cached) {
                const data = JSON.parse(cached);
                if (Date.now() - data.timestamp < 3600000) { // 1 hour cache
                    this.categories = data.categories;
                    this.wilayas = data.wilayas;
                    this.bycat = data.bycat;
                    this.initLoading = false;
                    return;
                }
            }

            const r = await fetch('/api/smart-booking/init');
            const d = await r.json();
            this.categories = d.categories;
            this.wilayas = d.wilayas;
            /* [FIX 1] تحميل الخدمات لكل تصنيف باستخدام Promise.allSettled لتجنب الفشل الكامل */
            const promises = d.categories.map(async c => {
                try {
                    const sr = await fetch('/api/smart-booking/services?category_id=' + c.id);
                    const svcs = await sr.json();
                    return { id: c.id, svcs };
                } catch (e) {
                    console.warn('Failed to load services for category', c.id, e);
                    return { id: c.id, svcs: [] };
                }
            });
            const results = await Promise.allSettled(promises);
            results.forEach(result => {
                if (result.status === 'fulfilled') {
                    this.bycat = { ...this.bycat, [result.value.id]: result.value.svcs };
                }
            });

            // Cache the data
            localStorage.setItem('smartBookingInit', JSON.stringify({
                categories: this.categories,
                wilayas: this.wilayas,
                bycat: this.bycat,
                timestamp: Date.now()
            }));
        } catch (e) {
            console.error('Init failed', e);
            this.err = 'فشل في تحميل البيانات. حاول مرة أخرى.';
        }
        this.initLoading = false;
    },

    // Debounced functions to reduce API calls
    debouncedOnWilaya: debounce(async function() {
        await this.onWilaya();
    }, 300),
    debouncedPickDate: debounce(async function(date) {
        await this.pickDate(date);
    }, 300),

    async pickSvc(svc){
        this.sel=svc;this.curPkg=null;this.isCustom=false;this.opts={};
        this.date=null;this.avail=null;this.err='';
        this.showVenueInput=false;
        this.pricing={base:0,options_cost:0,time_cost:0,travel_cost:0,subtotal:0,total:0,deposit:svc.deposit_amount??0};
        this.loadPkg=true;
        const r=await fetch('/api/smart-booking/packages?service_id='+svc.id);
        this.pkgs=await r.json();
        this.loadPkg=false;
        if(svc.show_venue_selector){const vr=await fetch('/api/smart-booking/venues');this.venues=await vr.json();}
        setTimeout(()=>document.getElementById('booking-form-start')?.scrollIntoView({behavior:'smooth',block:'start'}),100);
    },

    pickPkg(pkg){this.curPkg=pkg;this.isCustom=false;this.opts={};this.fetchPrice();},

    useCustom(){
        this.isCustom=true;
        this.curPkg=this.pkgs.find(p=>p.is_buildable)||null;
        this.opts={};
        this.custOpts=this.curPkg?.active_options??[];
        this.fetchPrice();
    },

    togOpt(opt){
        if(this.opts[opt.id])delete this.opts[opt.id];
        else this.opts[opt.id]=1;
        this.opts={...this.opts};
        this.fetchPrice();
    },

    async pickDate(s){
        this.date = s;
        this.avail = null;
        try {
            const r = await fetch('/api/smart-booking/availability?date=' + s + '&service_id=' + (this.sel?.id || ''));
            const d = await r.json();
            this.avail = d.status;
        } catch (e) {
            console.error('Availability check failed', e);
            this.avail = 'error';
        }
    },

    calcEnd(){
        if (!this.form.slot_start || !this.curPkg?.duration) return;
        const [h, m] = this.form.slot_start.split(':').map(Number);
        const e = new Date(2000, 0, 1, h, m + (this.curPkg.duration || 60));
        this.form.slot_end = e.toTimeString().slice(0, 5);
    },

    async onWilaya(){
        await this.fetchPrice();
        if (this.sel?.show_venue_selector) {
            try {
                const r = await fetch('/api/smart-booking/venues?wilaya_id=' + this.form.wilaya_id);
                this.venues = await r.json();
            } catch (e) {
                console.error('Venues load failed', e);
            }
        }
    },

    async fetchPrice(){
        if (!this.sel) return;
        try {
            /* [FIX 5] وقت النهاية دائماً 04:00 */
            const r = await fetch('/api/smart-booking/price', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({
                    service_id: this.sel.id,
                    package_id: this.curPkg?.id ?? null,
                    options: this.opts,
                    start_time: this.form.start_time || null,
                    end_time: '04:00',
                    venue_id: this.form.venue_id ?? null,
                    wilaya_id: this.form.wilaya_id ?? null
                }),
            });
            this.pricing = await r.json();
        } catch (e) {
            console.error('Price calculation failed', e);
            this.err = 'فشل في حساب السعر. حاول مرة أخرى.';
        }
    },

    applyPromo(){
        if(!this.form.promo_code)return;
        this.promoMsg='سيتم تطبيق الرمز عند تأكيد الحجز';
        this.promoOk=false;
    },

    ok(){
        const phoneRegex = /^0[5-7]\d{8}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return !!(
            this.sel &&
            (this.curPkg || this.isCustom) &&
            this.form.name.trim() &&
            emailRegex.test(this.form.email) &&
            phoneRegex.test(this.form.phone)
        );
    },

    async go(){
        if (!this.ok()) return;
        const now = Date.now();
        if (now - this.lastSubmit < 5000) { // 5 seconds cooldown
            this.err = 'يرجى الانتظار قبل الإرسال مرة أخرى.';
            return;
        }
        this.lastSubmit = now;
        this.busy = true;
        this.err = '';
        try {
            /* [FIX 5] end_time ثابت 04:00 دائماً */
            const p = {
                name: this.form.name,
                email: this.form.email,
                phone: this.form.phone,
                notes: this.form.notes,
                promo_code: this.form.promo_code || null,
                service_id: this.sel.id,
                type: this.btype,
                package_id: this.curPkg?.id ?? null,
                package_name: this.curPkg?.name ?? (this.isCustom ? 'مخصصة' : null),
                package_snapshot: this.curPkg ? { name: this.curPkg.name, price: this.curPkg.price } : null,
                selected_options: this.opts,
                event_date: this.date,
                appointment_date: this.date,
                start_time: this.form.start_time || null,
                end_time: '04:00',
                slot_start: this.form.slot_start || null,
                slot_end: this.form.slot_end || null,
                venue_id: this.form.venue_id ?? null,
                venue_custom: this.form.venue_custom || null,
                wilaya_id: this.form.wilaya_id ?? null
            };
            const r = await fetch('/api/smart-booking/submit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify(p)
            });
            const d = await r.json();
            if (d.success) {
                this.done = d;
                this.step = 'done';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                this.err = d.error || Object.values(d.errors || {}).flat().join(' — ');
            }
        } catch (e) {
            console.error('Submit failed', e);
            this.err = 'فشل في الإرسال. حاول مرة أخرى.';
        }
        this.busy = false;
    },

    calTitle(){return new Date(this.calY,this.calM-1,1).toLocaleDateString('ar-DZ',{month:'long',year:'numeric'});},
    calNext(){this.calM===12?(this.calM=1,this.calY++):this.calM++;},
    calPrev(){this.calM===1?(this.calM=12,this.calY--):this.calM--;},
    calOff(){const o=(new Date(this.calY,this.calM-1,1).getDay()+1)%7;return Array.from({length:o},(_,i)=>i);},
    calDays(){
        const days=new Date(this.calY,this.calM,0).getDate();
        const today=new Date();today.setHours(0,0,0,0);
        return Array.from({length:days},(_,i)=>{
            const d=i+1,dt=new Date(this.calY,this.calM-1,d);
            const s=`${this.calY}-${String(this.calM).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            return{d,s,p:dt<today,t:dt.getTime()===today.getTime()};
        });
    },

    n(v){return Number(v||0).toLocaleString('ar-DZ');},
    fd(s){if(!s)return'';return new Date(s+'T00:00:00').toLocaleDateString('ar-DZ',{day:'numeric',month:'long',year:'numeric'});},
};}
</script>
@endpush

@endsection