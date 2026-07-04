<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web'],
], function () {
    // Public routes
    Route::get('/contact', function () {
        return view('starter-kit::contact');
    })->name('contact');

    Route::post('/contact', [\Elmasry\StarterKit\Http\Controllers\ContactController::class, 'store'])
        ->name('contact.store');

    Route::post('/newsletter/subscribe', [\Elmasry\StarterKit\Http\Controllers\NewsletterController::class, 'subscribe'])
        ->name('newsletter.subscribe');

    // Language switcher
    Route::get('/language/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'ar'])) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }
        return redirect()->back();
    })->name('language.switch');
});
