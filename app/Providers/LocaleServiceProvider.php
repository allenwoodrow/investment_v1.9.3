<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            // Default to Portuguese if no session locale
            $locale = Session::get('locale', 'pt');
            
            if (in_array($locale, ['pt', 'en', 'es'])) {
                App::setLocale($locale);
            } else {
                App::setLocale('pt'); // Fallback to Portuguese
            }
        });
    }

    public function register()
    {
        //
    }
}
