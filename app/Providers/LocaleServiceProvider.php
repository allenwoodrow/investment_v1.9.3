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
            $localePreferred = (bool) Session::get('locale_preferred', false);
            $locale = Session::get('locale', 'en');

            if ($localePreferred && in_array($locale, ['pt', 'en', 'es'])) {
                App::setLocale($locale);
            } else {
                Session::put('locale', 'en');
                Session::put('locale_preferred', false);
                App::setLocale('en');
            }
        });
    }

    public function register()
    {
        //
    }
}
