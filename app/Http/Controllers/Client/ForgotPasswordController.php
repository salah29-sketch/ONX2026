<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\Client\PasswordResetOtpMail;
use App\Models\Client\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController extends Controller
{
    // POST /client/forgot-password/send
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'أدخل بريدًا إلكترونيًا صحيحًا.',
        ]);

        $key = 'otp-send:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'success' => false,
                'errors'  => ['email' => ['طلبات كثيرة. حاول بعد ' . ceil(RateLimiter::availableIn($key) / 60) . ' دقائق.']],
            ], 429);
        }
        RateLimiter::hit($key, 300);

        $email = strtolower(trim($request->email));

        $client = Client::where('email', $email)->first()
                  ?? Client::where('phone', $email)->first();

        $devOtp = null;

        if ($client) {
            if (!$client->email) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['email' => ['هذا الحساب ليس له بريد إلكتروني. تواصل مع الإدارة لاستعادة كلمة المرور.']],
                ], 422);
            }

            $code     = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $otpEmail = strtolower($client->email);

            DB::table('client_password_otps')->where('email', $otpEmail)->delete();
            DB::table('client_password_otps')->insert([
                'email'      => $otpEmail,
                'code'       => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
                'attempts'   => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            try {
                Mail::to($client->email)->send(new PasswordResetOtpMail($code, $client->name));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('OTP Mail failed: ' . $e->getMessage());
            }

            session(['otp_email' => $otpEmail]);

            if (app()->isLocal()) {
                $devOtp = $code;
            }
        } else {
            session(['otp_email' => $email]);
        }

        $res = ['success' => true];
        if ($devOtp) $res['dev_otp'] = $devOtp;

        return response()->json($res);
    }

    // POST /client/forgot-password/verify
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'أدخل الكود.',
            'code.size'     => 'الكود يجب أن يتكون من 6 أرقام.',
        ]);

        $email = session('otp_email');
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'انتهت الجلسة. ابدأ من جديد.'], 422);
        }

        $key = 'otp-verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'errors'  => ['code' => ['محاولات كثيرة. حاول بعد ' . ceil(RateLimiter::availableIn($key) / 60) . ' دقائق.']],
            ], 429);
        }

        $row = DB::table('client_password_otps')->where('email', $email)->first();

        if (!$row) {
            return response()->json(['success' => false, 'errors' => ['code' => ['الكود غير صالح. اطلب كوداً جديداً.']]], 422);
        }
        if (now()->greaterThan($row->expires_at)) {
            DB::table('client_password_otps')->where('email', $email)->delete();
            return response()->json(['success' => false, 'errors' => ['code' => ['انتهت صلاحية الكود. اطلب كوداً جديداً.']]], 422);
        }
        if ($row->attempts >= 5) {
            DB::table('client_password_otps')->where('email', $email)->delete();
            return response()->json(['success' => false, 'errors' => ['code' => ['تجاوزت عدد المحاولات. اطلب كوداً جديداً.']]], 422);
        }
        if (!Hash::check($request->code, $row->code)) {
            RateLimiter::hit($key, 900);
            DB::table('client_password_otps')->where('email', $email)->increment('attempts');
            $rem = 5 - (int) $row->attempts - 1;
            return response()->json(['success' => false, 'errors' => ['code' => [
                'الكود غير صحيح.' . ($rem > 0 ? " متبقي {$rem} محاولات." : ' اطلب كوداً جديداً.'),
            ]]], 422);
        }

        return response()->json(['success' => true]);
    }

    // POST /client/forgot-password/reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'code'                  => 'required|string|size:6',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'code.required'                  => 'أدخل الكود المرسل إليك.',
            'code.size'                      => 'الكود يجب أن يتكون من 6 أرقام.',
            'password.required'              => 'كلمة المرور مطلوبة.',
            'password.min'                   => 'كلمة المرور يجب أن لا تقل عن 6 أحرف.',
            'password.confirmed'             => 'كلمة المرور وتأكيدها غير متطابقتان.',
            'password_confirmation.required' => 'تأكيد كلمة المرور مطلوب.',
        ]);

        $email = session('otp_email');
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'انتهت الجلسة. ابدأ من جديد.'], 422);
        }

        $key = 'otp-verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'errors'  => ['code' => ['محاولات كثيرة. حاول بعد ' . ceil(RateLimiter::availableIn($key) / 60) . ' دقائق.']],
            ], 429);
        }

        $row = DB::table('client_password_otps')->where('email', $email)->first();

        if (!$row) {
            return response()->json(['success' => false, 'errors' => ['code' => ['الكود غير صالح. اطلب كوداً جديداً.']]], 422);
        }
        if (now()->greaterThan($row->expires_at)) {
            DB::table('client_password_otps')->where('email', $email)->delete();
            return response()->json(['success' => false, 'errors' => ['code' => ['انتهت صلاحية الكود (10 دقائق). اطلب كوداً جديداً.']]], 422);
        }
        if ($row->attempts >= 5) {
            DB::table('client_password_otps')->where('email', $email)->delete();
            return response()->json(['success' => false, 'errors' => ['code' => ['تجاوزت عدد المحاولات. اطلب كوداً جديداً.']]], 422);
        }
        if (!Hash::check($request->code, $row->code)) {
            RateLimiter::hit($key, 900);
            DB::table('client_password_otps')->where('email', $email)->increment('attempts');
            $rem = 5 - ($row->attempts + 1);
            return response()->json(['success' => false, 'errors' => ['code' => [
                'الكود غير صحيح.' . ($rem > 0 ? " متبقي {$rem} محاولات." : ' اطلب كوداً جديداً.'),
            ]]], 422);
        }

        $client = Client::where('email', $email)->first();
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ غير متوقع.'], 500);
        }

        $client->password = $request->password;
        $client->save();

        DB::table('client_password_otps')->where('email', $email)->delete();
        session()->forget('otp_email');
        RateLimiter::clear($key);
        Auth::guard('client')->login($client);

        return response()->json(['success' => true, 'redirect' => route('client.dashboard')]);
    }

    // POST /client/forgot-password/resend
    public function resendOtp(Request $request)
    {
        $email = session('otp_email') ?? $request->input('email');
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'البريد غير محدد.'], 422);
        }

        $key = 'otp-resend:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return response()->json([
                'success' => false,
                'message' => 'انتظر ' . RateLimiter::availableIn($key) . ' ثانية قبل طلب كود جديد.',
            ], 429);
        }
        RateLimiter::hit($key, 120);

        $client = Client::where('email', $email)->first();
        $devOtp = null;

        if ($client) {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            DB::table('client_password_otps')->where('email', $email)->delete();
            DB::table('client_password_otps')->insert([
                'email'      => $email,
                'code'       => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
                'attempts'   => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            try {
                Mail::to($client->email)->send(new PasswordResetOtpMail($code, $client->name));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('OTP Resend Mail failed: ' . $e->getMessage());
            }
            if (app()->isLocal()) $devOtp = $code;
        }

        $res = ['success' => true];
        if ($devOtp) $res['dev_otp'] = $devOtp;
        return response()->json($res);
    }
}