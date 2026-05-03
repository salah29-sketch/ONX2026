<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('front.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
        ], [
            'name.required'    => 'الاسم مطلوب.',
            'email.required'  => 'البريد الإلكتروني مطلوب.',
            'email.email'     => 'أدخل بريدًا إلكترونيًا صحيحًا.',
            'message.required' => 'الرسالة مطلوبة.',
        ]);

        $to = config('mail.from.address');

        try {
            Mail::to($to)->send(new ContactFormMail($validated));
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['message' => 'حدث خطأ أثناء الإرسال. يرجى المحاولة لاحقًا أو التواصل عبر واتساب.']);
        }

        return redirect()
            ->route('contact')
            ->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك في أقرب وقت.');
    }
}
