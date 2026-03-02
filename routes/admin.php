<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MediaController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // ---- Guest-only (not logged in) ----
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // ---- Authenticated admin ----
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Bookings (manual resource + custom actions)
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/',                        [BookingController::class, 'index'])->name('index');
            Route::get('/create',                  [BookingController::class, 'create'])->name('create');
            Route::post('/',                       [BookingController::class, 'store'])->name('store');
            Route::get('/slots/{date}',            [BookingController::class, 'slots'])->name('slots');
            Route::get('/{booking}',               [BookingController::class, 'show'])->name('show');
            Route::patch('/{booking}/confirm',     [BookingController::class, 'confirm'])->name('confirm');
            Route::patch('/{booking}/assign',      [BookingController::class, 'assign'])->name('assign');
            Route::patch('/{booking}/in-progress', [BookingController::class, 'inProgress'])->name('in-progress');
            Route::patch('/{booking}/reschedule',  [BookingController::class, 'reschedule'])->name('reschedule');
            Route::patch('/{booking}/complete',    [BookingController::class, 'complete'])->name('complete');
            Route::patch('/{booking}/cancel',      [BookingController::class, 'cancel'])->name('cancel');
            Route::patch('/{booking}/notes',       [BookingController::class, 'notes'])->name('notes');

            Route::get('/{booking}/invoice/create', [\App\Http\Controllers\Admin\InvoiceController::class, 'create'])->name('invoice.create');
            Route::post('/{booking}/invoice', [\App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('invoice.store');
            Route::get('/{booking}/invoice/{invoice}', [\App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoice.show');
            Route::put('/{booking}/invoice/{invoice}', [\App\Http\Controllers\Admin\InvoiceController::class, 'update'])->name('invoice.update');
            Route::patch('/{booking}/invoice/{invoice}/send', [\App\Http\Controllers\Admin\InvoiceController::class, 'send'])->name('invoice.send');
            Route::patch('/{booking}/invoice/{invoice}/mark-paid', [\App\Http\Controllers\Admin\InvoiceController::class, 'markPaid'])->name('invoice.mark-paid');
            Route::get('/{booking}/invoice/{invoice}/download', [\App\Http\Controllers\Admin\InvoiceController::class, 'download'])->name('invoice.download');

        });

        // Services & Categories
        Route::resource('services', ServiceController::class)->names('services');

        // Technicians
        Route::resource('technicians', TechnicianController::class)->names('technicians');

        // Blog
        Route::resource('blog', BlogController::class)->names('blog');

        // Customers (read-only)
        Route::get('/customers',      [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

        // Consultations
        Route::get('/consultations',        [ConsultationController::class, 'index'])->name('consultations.index');
        Route::get('/consultations/{id}',   [ConsultationController::class, 'show'])->name('consultations.show');
        Route::patch('/consultations/{id}', [ConsultationController::class, 'update'])->name('consultations.update');

        // Reports
        Route::get('/reports',        [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Settings
        Route::get('/settings',  [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings',  [SettingsController::class, 'update'])->name('settings.update');

        // Media Library Inertia page
        Route::get('/media-library', function () {
            return \Inertia\Inertia::render('Admin/Media/Index');
        })->name('media.page');

        // Media library — image upload, browse, delete
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/',         [MediaController::class, 'index'])->name('index');
            Route::post('/',        [MediaController::class, 'store'])->name('store');
            Route::patch('/{media}',[MediaController::class, 'update'])->name('update');
            Route::delete('/{media}',[MediaController::class, 'destroy'])->name('destroy');
        });
    });
});