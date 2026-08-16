<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TranslationController as AdminTranslationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PayPalWebhookController;

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

// التعريبات
Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
Route::get('/translations/{id}', [TranslationController::class, 'show'])->name('translations.show');

// التحميل
Route::get('/download/{id}', [TranslationController::class, 'download'])->name('translations.download');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// PayPal Webhook
Route::post('/paypal/webhook', [PayPalWebhookController::class, 'handle'])->name('paypal.webhook');

// الاشتراكات
Route::middleware(['auth'])->group(function () {
    Route::get('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});

// لوحة الإدارة
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('translations', AdminTranslationController::class);
    Route::resource('users', UserController::class);
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');