@extends('layouts.mail')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%" dir="rtl" style="font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
    <tr>
        <td align="center" bgcolor="#eeeeee">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" style="padding: 35px;" bgcolor="#c95518">
                        <h1 style="font-size: 28px; color: #fff; font-family: Arial, sans-serif;">تأكيد الحجز — ONX</h1>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 35px; background-color: #ffffff; text-align: right;">
                        <p style="font-size: 48px; margin: 0 0 10px 0;">✓</p>
                        <h2 style="font-family: Arial, sans-serif; color: #333;">مرحباً {{ $data['client_name'] ?? 'عزيزي العميل' }}</h2>
                        <p style="font-family: Arial, sans-serif; color: #555; font-size: 16px;">
                            تم تسجيل حجزك بنجاح. إليك ملخص الطلب:
                        </p>

                        <table width="100%" style="margin-top: 20px; font-family: Arial, sans-serif; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>رقم الحجز:</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">{{ $data['booking_id'] ?? '—' }}</td>
                            </tr>
                            @if(!empty($data['date']))
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>التاريخ:</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">{{ $data['date'] }}</td>
                            </tr>
                            @endif
                            @if(!empty($data['time']))
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>الوقت:</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">{{ $data['time'] }}</td>
                            </tr>
                            @endif
                            @if(!empty($data['services']) && is_array($data['services']))
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;"><strong>الخدمات / الباقة:</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                    <ul style="margin: 0; padding-right: 20px;">
                                        @foreach($data['services'] as $service)
                                            <li>{{ $service }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @endif
                            @if(isset($data['total_price']))
                            <tr>
                                <td style="padding: 10px 0;"><strong>المبلغ الإجمالي:</strong></td>
                                <td style="padding: 10px 0;">{{ $data['total_price'] ?? '0' }} دج</td>
                            </tr>
                            @endif
                        </table>

                        <p style="margin-top: 30px; font-family: Arial, sans-serif; color: #888; font-size: 14px;">
                            سنتواصل معك قريباً لتأكيد التفاصيل النهائية. شكراً لثقتك في ONX.
                        </p>
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
