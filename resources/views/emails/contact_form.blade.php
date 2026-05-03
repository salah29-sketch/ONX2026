@extends('layouts.mail')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" bgcolor="#eeeeee">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" style="padding: 35px;" bgcolor="#c95518">
                        <h1 style="font-size: 28px; color: #fff; font-family: Arial, sans-serif;">رسالة تواصل جديدة - ONX</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 25px 35px; background-color: #ffffff; font-family: Arial, sans-serif; color: #333;">
                        <p style="font-size: 16px; margin-bottom: 20px;">وصلت رسالة من نموذج «تواصل معنا» في الموقع.</p>
                        <table width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>الاسم:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $data['name'] ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>البريد:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $data['email'] ?? '—' }}</td>
                            </tr>
                            @if(!empty($data['phone']))
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>الهاتف:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $data['phone'] }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding: 8px 0; vertical-align: top;"><strong>الرسالة:</strong></td>
                                <td style="padding: 8px 0;">{{ $data['message'] ?? '—' }}</td>
                            </tr>
                        </table>
                        <p style="margin-top: 25px; color: #888; font-size: 14px;">يمكنك الرد مباشرة على هذا البريد للتواصل مع المرسل.</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#c95518" style="padding: 20px; color: #fff; font-family: Arial, sans-serif;">
                        © {{ date('Y') }} ONX — جميع الحقوق محفوظة.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
