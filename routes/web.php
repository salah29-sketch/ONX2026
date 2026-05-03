<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\PortfolioController;
use App\Http\Controllers\Front\BookingController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\FaqController;
use App\Models\Service\Service;

/*
|--------------------------------------------------------------------------
| Front Routes — الواجهة الأمامية
|--------------------------------------------------------------------------
*/

// robots.txt ديناميكي (يستخدم APP_URL من .env)
Route::get('/robots.txt', function () {
    $base = rtrim(config('app.url'), '/');
    return response(
        "User-agent: *\nDisallow: /admin\nDisallow: /login\nAllow: /\n\nSitemap: {$base}/sitemap.xml\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
})->name('robots');

// Sitemap للأرشفة ومحركات البحث
Route::get('/sitemap.xml', function () {
    $base = rtrim(config('app.url'), '/');
    $urls = [
        ['loc' => $base . '/',                    'changefreq' => 'weekly',  'priority' => '1.0'],
        ['loc' => $base . '/portfolio',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => $base . '/services',            'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => $base . '/packages',            'changefreq' => 'weekly',  'priority' => '0.75'],
        ['loc' => $base . '/book',                'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => $base . '/booking/status',      'changefreq' => 'weekly',  'priority' => '0.85'],
        ['loc' => $base . '/contact',             'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => $base . '/faq',                 'changefreq' => 'monthly', 'priority' => '0.7'],
    ];
    foreach (Service::query()->where('is_active', true)->orderBy('sort_order')->pluck('slug') as $slug) {
        $urls[] = ['loc' => $base . '/services/' . $slug, 'changefreq' => 'weekly', 'priority' => '0.8'];
    }
    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        $xml .= '  <url><loc>' . htmlspecialchars($u['loc']) . '</loc>'
              . '<changefreq>' . $u['changefreq'] . '</changefreq>'
              . '<priority>' . $u['priority'] . '</priority></url>' . "\n";
    }
    $xml .= '</urlset>';
    return response($xml, 200, ['Content-Type' => 'application/xml', 'Charset' => 'UTF-8']);
})->name('sitemap');

// ── الصفحة الرئيسية ──────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── FAQ ──────────────────────────────────────────────────────────────────
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// ── صفحة حالة الخدمة (uptime) ───────────────────────────────────────────
// /status = صفحة عامة تعرض "جميع الأنظمة تعمل" (ليست تتبع الحجز)
Route::get('/status', function () {
    return view('front.status');
})->name('status');

// ── تواصل معنا ───────────────────────────────────────────────────────────
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// ── الأعمال ──────────────────────────────────────────────────────────────
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');

// ── الخدمات (ديناميكية من DB) ───────────────────────────────────────────
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/',           [ServiceController::class, 'index'])->name('index');
    Route::get('/{slug}',     [ServiceController::class, 'show'])->name('show')->where('slug', '[a-z0-9\-]+');
});

// ── صفحة الحزم ──────────────────────────────────────────────────────────
Route::get('/packages', [ServiceController::class, 'packages'])->name('front.packages');

// ── نظام الحجز الموحّد (Livewire) ────────────────────────────────────
Route::get('/book', function () {
    return view('front.booking.unified');
})->name('book');

// ── Redirects من النظام القديم ──────────────────────────────────────
Route::get('/booking', fn () => redirect()->route('book', status: 301))->name('booking');
Route::get('/book/{slug}', fn () => redirect()->route('book', status: 301))->name('booking.livewire');

// ── الحجز — صفحات لا تزال مطلوبة (تأكيد، PDF، تتبع) ──────────────
Route::prefix('booking')->group(function () {
    Route::get('/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/pdf/{booking}',          [BookingController::class, 'pdf'])->name('booking.pdf');
    Route::get('/status',                 [\App\Http\Controllers\Front\BookingStatusController::class, 'index'])->name('booking.status');
    Route::post('/status',                [\App\Http\Controllers\Front\BookingStatusController::class, 'search'])->middleware('throttle:10,1')->name('booking.status.search');
});


// ── Auth للإدارة فقط (بدون register / reset / verify) ───────────────────
Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| منطقة العملاء — Client Area
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->group(function () {

    // ── تسجيل الدخول ─────────────────────────────────────────────────────
    Route::get('login',  [\App\Http\Controllers\Client\AuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest:client');
    Route::post('login', [\App\Http\Controllers\Client\AuthController::class, 'login'])
        ->name('login.post')
        ->middleware('throttle:5,1');

    // ── استرجاع كلمة المرور (OTP — AJAX) ─────────────────────────────────
    Route::post('forgot-password/send', [\App\Http\Controllers\Client\ForgotPasswordController::class, 'sendOtp'])
        ->name('forgot-password.send')
        ->middleware('guest:client');

    Route::post('forgot-password/verify', [\App\Http\Controllers\Client\ForgotPasswordController::class, 'verifyOtp'])
        ->name('forgot-password.verify')
        ->middleware('guest:client');

    Route::post('forgot-password/reset', [\App\Http\Controllers\Client\ForgotPasswordController::class, 'resetPassword'])
        ->name('forgot-password.reset')
        ->middleware('guest:client');

    Route::post('forgot-password/resend', [\App\Http\Controllers\Client\ForgotPasswordController::class, 'resendOtp'])
        ->name('forgot-password.resend')
        ->middleware('guest:client');

    // GET — يحوّل لصفحة Login ويفتح الـ accordion تلقائياً
    Route::get('forgot-password', function () {
        return redirect()->route('client.login', ['forgot' => 1]);
    })->name('forgot-password')->middleware('guest:client');

    // ── ضبط كلمة المرور بعد الحجز ────────────────────────────────────────
    Route::get('set-password/{booking}', [\App\Http\Controllers\Client\AuthController::class, 'showSetPassword'])
        ->name('set-password')
        ->whereNumber('booking');
    Route::post('set-password', [\App\Http\Controllers\Client\AuthController::class, 'setPassword'])
        ->name('set-password.post');

    // ── منطقة العميل المحمية ──────────────────────────────────────────────
    Route::middleware('client.auth')->group(function () {

        Route::post('logout', [\App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [\App\Http\Controllers\Client\DashboardController::class, 'dashboard'])->name('dashboard');

        // Profile
        Route::get('profile', [\App\Http\Controllers\Client\DashboardController::class, 'profile'])->name('profile');
        Route::put('profile', [\App\Http\Controllers\Client\DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('password', [\App\Http\Controllers\Client\DashboardController::class, 'changePassword'])->name('password.update');

        // Bookings
        Route::get('bookings',          [\App\Http\Controllers\Client\DashboardController::class, 'bookings'])->name('bookings');
        Route::get('bookings/{booking}', [\App\Http\Controllers\Client\DashboardController::class, 'bookingDetail'])->name('bookings.show');

        // Booking documents
        Route::get('bookings/{booking}/invoice',     [\App\Http\Controllers\Client\DashboardController::class, 'invoicePdf'])->name('bookings.invoice');
        Route::get('bookings/{booking}/summary',     [\App\Http\Controllers\Client\DashboardController::class, 'bookingSummary'])->name('bookings.summary');
        Route::get('bookings/{booking}/booking-pdf', [\App\Http\Controllers\Client\DashboardController::class, 'bookingPdf'])->name('bookings.booking-pdf');

        // Messages
        Route::get('messages',  [\App\Http\Controllers\Client\DashboardController::class, 'messages'])->name('messages');
        Route::post('messages', [\App\Http\Controllers\Client\DashboardController::class, 'storeMessage'])->name('messages.store');

        // Reviews
        Route::get('review',  [\App\Http\Controllers\Client\DashboardController::class, 'createReview'])->name('review.create');
        Route::post('review', [\App\Http\Controllers\Client\DashboardController::class, 'storeReview'])->name('review.store');

        // Project photos
        Route::get('project-photos',                        [\App\Http\Controllers\Client\DashboardController::class, 'projectPhotos'])->name('project-photos');
        Route::get('project-photos/booking/{booking}',      [\App\Http\Controllers\Client\DashboardController::class, 'projectPhotosBooking'])->name('project-photos.booking');
        Route::post('project-photos/toggle',                [\App\Http\Controllers\Client\DashboardController::class, 'toggleSelectedPhoto'])->name('project-photos.toggle');
        Route::post('project-photos/booking/{booking}/zip', [\App\Http\Controllers\Client\DashboardController::class, 'downloadSelectedPhotosZip'])->name('project-photos.zip');

        // Payments
        Route::get('payments', [\App\Http\Controllers\Client\DashboardController::class, 'payments'])->name('payments');

        // Subscriptions
        Route::get('subscriptions',                                   [\App\Http\Controllers\Client\DashboardController::class, 'subscriptions'])->name('subscriptions');
        Route::post('subscriptions/{subscription}/renew',             [\App\Http\Controllers\Client\DashboardController::class, 'renewSubscription'])->name('subscriptions.renew');
        Route::put('subscriptions/{subscription}/renewal-type',       [\App\Http\Controllers\Client\DashboardController::class, 'updateSubscriptionRenewalType'])->name('subscriptions.renewal-type');

        // Media & Files
        Route::get('media', [\App\Http\Controllers\Client\DashboardController::class, 'media'])->name('media');
        Route::get('files', [\App\Http\Controllers\Client\DashboardController::class, 'files'])->name('files');

        // File download
        Route::get('files/{file}/download', [\App\Http\Controllers\Client\DashboardController::class, 'downloadFile'])->name('files.download');
    });
});

/*
|--------------------------------------------------------------------------
| منطقة العمال — Worker Area (Auth + Task Dashboard)
|--------------------------------------------------------------------------
*/
Route::prefix('worker')->name('worker.')->group(function () {
    Route::get('login', [\App\Http\Controllers\Worker\AuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest:worker');
    Route::post('login', [\App\Http\Controllers\Worker\AuthController::class, 'login'])
        ->name('login.post')
        ->middleware('throttle:5,1');

    Route::middleware('worker.auth')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Worker\AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [\App\Http\Controllers\Worker\DashboardController::class, 'index'])->name('dashboard');
    });
});