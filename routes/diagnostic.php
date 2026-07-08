<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('/diagnostic', function () {
    return response()->json([
        'app_locale' => App::getLocale(),
        'session_locale' => Session::get('locale'),
        'session_all' => Session::all(),
        'session_id' => Session::getId(),
        'config_locale' => config('app.locale'),
        'config_fallback' => config('app.fallback_locale'),
        'translation_test' => __('messages.home'),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
    ]);
});
