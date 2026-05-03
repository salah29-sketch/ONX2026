<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONX — كود إعادة تعيين كلمة المرور</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f5;
            color: #111827;
            padding: 32px 16px;
            direction: rtl;
        }
        .wrapper {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
        }
        .header {
            background: #0a0a0a;
            padding: 28px 32px;
            text-align: center;
        }
        .logo {
            font-size: 26px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 2px;
        }
        .logo span { color: #f97316; }
        .body { padding: 32px; }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        .text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .otp-box {
            background: #fff7ed;
            border: 2px dashed #fdba74;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        .otp-code {
            font-size: 40px;
            font-weight: 900;
            color: #ea580c;
            letter-spacing: 10px;
            direction: ltr;
        }
        .otp-expiry {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 10px;
        }
        .warn-box {
            background: #fef2f2;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 13px;
            color: #b91c1c;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 32px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
        }
        .footer p {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.7;
        }
        .footer strong { color: #6b7280; }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <div class="logo">ON<span>X</span></div>
        </div>

        {{-- Body --}}
        <div class="body">
            @if($clientName)
            <p class="greeting">مرحباً {{ $clientName }}،</p>
            @else
            <p class="greeting">مرحباً،</p>
            @endif

            <p class="text">
                تلقّينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك في ONX.<br>
                استخدم الكود أدناه لإتمام العملية:
            </p>

            {{-- OTP Code --}}
            <div class="otp-box">
                <p class="otp-label">كود التحقق</p>
                <p class="otp-code">{{ $code }}</p>
                <p class="otp-expiry">⏱ ينتهي خلال <strong>10 دقائق</strong></p>
            </div>

            {{-- Warning --}}
            <div class="warn-box">
                ⚠️ إذا لم تطلب إعادة تعيين كلمة المرور، يُرجى تجاهل هذا البريد.
                لا تشارك هذا الكود مع أي شخص.
            </div>

            <p class="text" style="margin-bottom:0;">
                إذا واجهت أي مشكلة، تواصل معنا عبر منصاتنا.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                <strong>ONX — الإعلام والتصوير</strong><br>
                هذا البريد مُرسَل تلقائياً، يُرجى عدم الرد عليه.
            </p>
        </div>
    </div>
</body>
</html>
