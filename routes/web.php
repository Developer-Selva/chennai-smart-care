<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\BookingController as UserBooking;
use App\Http\Controllers\Api\QuickBookingController;

/*
|--------------------------------------------------------------------------
| PUBLIC / LANDING
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/services/{slug}', [LandingController::class, 'service'])->name('service.show');
Route::get('/services/{slug}/{areaSlug}', [LandingController::class, 'serviceArea'])->name('service.area');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/free-consultation', [LandingController::class, 'consultationPage'])->name('consultation');
Route::post('/free-consultation', [LandingController::class, 'freeConsultation'])->name('consultation.store');

/*
|--------------------------------------------------------------------------
| QUICK BOOKING (Public — no login required)
|--------------------------------------------------------------------------
*/
Route::get('/quick-booking', [LandingController::class, 'quickBooking'])->name('quick-booking');
Route::post('/quick-booking', [QuickBookingController::class, 'store'])->name('quick-booking.store');
Route::get('/track/{bookingNumber}', [LandingController::class, 'trackBooking'])->name('booking.track');

/*
|--------------------------------------------------------------------------
| BLOG
|--------------------------------------------------------------------------
*/
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [LandingController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [LandingController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| USER AUTH (Guests only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| USER AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserAuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/bookings/create', [UserBooking::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [UserBooking::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{bookingNumber}', [UserDashboard::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{bookingNumber}/review', [UserBooking::class, 'review'])->name('bookings.review');
    Route::post('/bookings/{bookingNumber}/cancel', [UserBooking::class, 'cancel'])->name('bookings.cancel');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\User\InvoiceController::class, 'show'])->name('invoice.show');
});

/*
|--------------------------------------------------------------------------
| LEGAL PAGES
|--------------------------------------------------------------------------
*/
Route::get('/offline', fn() => response()->file(public_path('offline.html')))->name('offline');

Route::get('/privacy-policy',         [LandingController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms',                  [LandingController::class, 'terms'])->name('terms');
Route::get('/refund-policy',          [LandingController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/disclaimer',             [LandingController::class, 'disclaimer'])->name('disclaimer');
Route::get('/service-warranty-policy',[LandingController::class, 'warrantyPolicy'])->name('warranty-policy');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/free-consultation', [LandingController::class, 'consultationPage'])->name('consultation');
Route::post('/free-consultation', [LandingController::class, 'freeConsultation'])->name('consultation.store');
