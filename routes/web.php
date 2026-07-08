<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

// Static Blade pages
Route::get('/', function () { return view('welcome'); });
Route::view('/about', 'about');
Route::view('/services', 'services');
Route::view('/plans', 'plans');
Route::view('/contact', 'contact');
Route::post('/subscribe', function () { return back(); });

// Language
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');
Route::post('/language/switch-ajax', [LanguageController::class, 'switchAjax'])->name('language.switch.ajax');

// API routes
Route::prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgot_password']);
    Route::post('/reset-password', [AuthController::class, 'reset_password']);
});

// Email verification - MUST be before catch-all
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

// Vue SPA routes
Route::get('/auth/{any?}', function () { return view('app'); })->where('any', '.*');
Route::get('/account/{any?}', function () { return view('app'); })->where('any', '.*');
Route::get('/admin/{any?}', function () { return view('app'); })->where('any', '.*');

// Catch-all - LAST
Route::get('/{any?}', function () { return view('app'); })->where('any', '.*');