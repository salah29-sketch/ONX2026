<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>حجز — {{ $service->name }} | ONX Edge</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
    --onx:#c8490a;--onx2:#e05510;--onx3:#ff6b1a;
    --dim:#1e0a02;--bg:#080808;--sur:#0f0f0f;--sur2:#141414;
    --bdr:#1f1f1f;--bdr2:#2a2a2a;
    --txt:#ffffff;--mut:#555;--mut2:#888;
}
body{font-family:'Cairo',sans-serif;background:var(--bg);color:var(--txt);min-height:100vh;direction:rtl}

/* NAV */
.nav{padding:18px 40px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--bdr);background:rgba(8,8,8,.95);position:sticky;top:0;z-index:50}
.nav-logo{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--txt);text-decoration:none}
.nav-logo span{color:var(--onx)}
.nav-back{font-size:13px;color:var(--mut2);text-decoration:none;transition:.2s}
.nav-back:hover{color:var(--onx)}

/* PAGE */
.page{max-width:960px;margin:0 auto;padding:40px 24px}
.svc-header{margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--bdr)}
.svc-tag{font-size:11px;color:var(--onx);font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px}
.svc-title{font-family:'Syne',sans-serif;font-size:clamp(28px,4vw,44px);font-weight:800;letter-spacing:-1px}
.svc-desc{font-size:14px;color:var(--mut2);margin-top:8px;font-weight:300}

/* FORM PANEL */
.form-panel{background:var(--sur);border:1px solid var(--bdr);border-radius:20px;overflow:hidden}
.form-panel-head{padding:20px 28px;border-bottom:1px solid var(--bdr);position:relative}
.form-panel-head::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--onx),var(--onx3))}
.form-panel-body{padding:28px}

/* STEPS BAR */
.steps-bar{display:flex;gap:0;margin-bottom:24px;background:var(--sur2);border-radius:12px;padding:4px}
.step-btn{flex:1;padding:8px;text-align:center;font-size:11px;font-weight:600;color:var(--mut);border-radius:9px;transition:.2s}
.step-btn.active{background:var(--onx);color:#fff}
.step-btn.done{color:var(--onx)}

/* SECTION LABEL */
.sec-label{font-size:11px;color:var(--onx);font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.sec-label::before{content:'';width:16px;height:1px;background:var(--onx)}

/* PACKAGES */
.packages-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:16px}
.pkg-card{background:var(--sur2);border:1px solid var(--bdr2);border-radius:14px;padding:18px 16px;cursor:pointer;transition:.2s;position:relative;overflow:hidden}
.pkg-card::before{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:var(--onx);transform:scaleX(0);transition:.3s}
.pkg-card:hover{transform:translateY(-2px)}
.pkg-card.selected{border-color:var(--onx);background:var(--dim)}
.pkg-card.selected::before{transform:scaleX(1)}
.pkg-name{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--txt);margin-bottom:4px}
.pkg-price{font-size:18px;font-weight:800;color:var(--onx);font-family:'Syne',sans-serif}
.pkg-unit{font-size:10px;color:var(--mut2)}
.pkg-desc{font-size:11px;color:var(--mut2);margin-top:6px;line-height:1.6}
.pkg-check{position:absolute;top:10px;left:10px;width:18px;height:18px;background:var(--onx);border-radius:50%;display:none;align-items:center;justify-content:center;font-size:10px}
.pkg-card.selected .pkg-check{display:flex}

/* CUSTOM TOGGLE */
.custom-toggle{width:100%;background:transparent;border:1px dashed var(--bdr2);color:var(--mut2);padding:12px;border-radius:12px;font-size:13px;cursor:pointer;font-family:'Cairo',sans-serif;transition:.2s;margin-bottom:20px}
.custom-toggle:hover,.custom-toggle.active{border-color:var(--onx);color:var(--onx)}
.custom-toggle.active{background:var(--dim)}

/* OPTIONS */
.options-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
@media(max-width:500px){.options-grid{grid-template-columns:1fr}}
.opt-card{background:var(--sur2);border:1px solid var(--bdr2);border-radius:12px;padding:14px;cursor:pointer;transition:.2s;display:flex;align-items:center;gap:12px}
.opt-card.selected{border-color:var(--onx);background:var(--dim)}
.opt-check{width:20px;height:20px;border:1px solid var(--bdr2);border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;transition:.2s}
.opt-card.selected .opt-check{background:var(--onx);border-color:var(--onx)}
.opt-name{font-size:12px;font-weight:600;color:var(--txt);margin-bottom:2px}
.opt-price{font-size:11px;color:var(--onx);font-weight:700}

/* FIELDS */
.field-label{font-size:12px;color:var(--mut2);margin-bottom:6px;font-weight:600}
.field-input{width:100%;background:var(--sur2);border:1px solid var(--bdr2);color:var(--txt);padding:12px 14px;border-radius:10px;font-size:13px;font-family:'Cairo',sans-serif;outline:none;transition:.2s}
.field-input:focus{border-color:var(--onx)}
.field-input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(.4)}
.field-input[type="time"]::-webkit-calendar-picker-indicator{filter:invert(.4)}

/* AVAILABILITY */
.avail-badge{display:inline-flex;align-items:center;gap:6px;font-size:11px;padding:4px 12px;border-radius:20px;margin-top:6px;font-weight:600}
.avail-ok{background:#0a2010;color:#4ade80;border:1px solid #1a4020}
.avail-no{background:#200a0a;color:#f87171;border:1px solid #401a1a}
.avail-loading{background:#1a1500;color:#fbbf24;border:1px solid #3a2d00}

/* TIME */
.time-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.time-note{font-size:11px;color:var(--mut);margin-top:4px;line-height:1.5}
.time-cost{font-size:12px;color:var(--onx);font-weight:700;margin-top:6px}

/* INFO GRID */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
@media(max-width:500px){.info-grid{grid-template-columns:1fr}}

/* STEP ACTIONS */
.step-actions{display:flex;gap:10px;margin-top:28px;padding-top:20px;border-top:1px solid var(--bdr)}
.btn-next{flex:1;background:var(--onx);color:#fff;border:none;padding:14px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Cairo',sans-serif;transition:.2s}
.btn-next:hover{background:var(--onx2)}
.btn-prev{background:transparent;color:var(--mut2);border:1px solid var(--bdr2);padding:14px 20px;border-radius:12px;font-size:13px;cursor:pointer;font-family:'Cairo',sans-serif;transition:.2s}
.btn-prev:hover{border-color:var(--onx);color:var(--onx)}
.btn-submit{flex:1;background:var(--onx);color:#fff;border:none;padding:14px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Cairo',sans-serif;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-submit:hover{background:var(--onx2)}

/* ERRORS */
.error-msg{font-size:11px;color:#f87171;margin-top:4px}
.alert-box{background:#200a0a;border:1px solid #401a1a;color:#f87171;padding:12px 16px;border-radius:10px;font-size:12px;margin-bottom:16px}

/* SUM ROWS */
.sum-row{display:flex;justify-content:space-between;font-size:12px;padding:6px 0;border-bottom:1px solid #141414}
.sum-row:last-child{border:none}
.sum-label{color:var(--mut2)}
.sum-val{font-weight:600;color:var(--txt)}
.sum-total{display:flex;justify-content:space-between;align-items:center;padding:12px 0}

/* CONFIRMATION */
.confirm-box{padding:40px 28px;text-align:center}
.confirm-circle{width:72px;height:72px;background:var(--dim);border:2px solid var(--onx);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:32px}
.confirm-title{font-family:'Syne',sans-serif;font-size:22px;font-weight:800;margin-bottom:8px}
.confirm-sub{font-size:13px;color:var(--mut2);margin-bottom:28px;line-height:1.7}
.confirm-ref{display:inline-block;background:var(--dim);border:1px solid var(--onx);color:var(--onx);font-family:'Syne',sans-serif;font-size:14px;font-weight:800;padding:6px 18px;border-radius:20px;margin-bottom:24px;letter-spacing:1px}
.deposit-warn{background:#1a0800;border:1px solid var(--onx);border-radius:14px;padding:16px;margin-bottom:24px;text-align:right}
.dw-title{font-size:13px;font-weight:700;color:var(--onx);margin-bottom:4px}
.dw-desc{font-size:11px;color:#a06040;line-height:1.7}
.dw-amount{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--onx);margin-top:6px}
.creds-box{background:#0a0a0a;border:1px solid var(--bdr2);border-radius:14px;padding:16px;margin-bottom:20px;text-align:right}
.creds-title{font-size:11px;color:var(--mut);margin-bottom:12px;font-weight:600;letter-spacing:.5px}
.cred-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #1a1a1a}
.cred-row:last-child{border:none}
.cred-label{font-size:11px;color:var(--mut)}
.cred-val{font-family:'Syne',sans-serif;font-size:12px;font-weight:700;background:#141414;padding:4px 10px;border-radius:6px;border:1px solid var(--bdr2)}
.creds-once{font-size:10px;color:#664433;margin-top:8px;display:flex;align-items:center;gap:6px}
.btn-dashboard{display:block;width:100%;background:var(--onx);color:#fff;text-align:center;padding:14px;border-radius:12px;font-size:14px;font-weight:700;text-decoration:none;transition:.2s;margin-bottom:10px}
.btn-dashboard:hover{background:var(--onx2)}
.btn-home{display:block;width:100%;background:transparent;color:var(--mut2);text-align:center;padding:12px;border-radius:12px;font-size:12px;text-decoration:none;border:1px solid var(--bdr2);transition:.2s}
.btn-home:hover{border-color:var(--onx);color:var(--onx)}

/* VENUE OR */
.venue-or{text-align:center;font-size:11px;color:var(--mut);margin:10px 0}
.location-section{margin-bottom:20px}
</style>
</head>
<body>

<nav class="nav">
    <a href="/" class="nav-logo">ONX <span>Edge</span></a>
    <a href="/booking" class="nav-back">← نظام الحجز</a>
</nav>

<div class="page">
    <div class="svc-header">
        <div class="svc-tag">حجز خدمة</div>
        <h1 class="svc-title">{{ $service->name }}</h1>
        @if($service->description)
            <p class="svc-desc">{{ $service->description }}</p>
        @endif
    </div>

    @livewire('event-booking-form', ['serviceId' => $service->id])
</div>

</body>
</html>
