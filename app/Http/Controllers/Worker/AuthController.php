<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Worker\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('worker')->check()) {
            return redirect()->route('worker.dashboard');
        }
        return view('worker.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        $throttleKey = 'worker-login:' . mb_strtolower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "محاولات كثيرة. حاول مجدداً بعد {$seconds} ثانية.",
            ])->withInput();
        }

        $worker = Worker::where('email', $request->email)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            RateLimiter::hit($throttleKey, 300);
            return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])->withInput();
        }

        if (!$worker->is_active) {
            return back()->withErrors(['email' => 'هذا الحساب غير مفعّل.'])->withInput();
        }

        RateLimiter::clear($throttleKey);
        Auth::guard('worker')->login($worker, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended(route('worker.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('worker')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('worker.login');
    }
}
