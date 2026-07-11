<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $localePreferred = (bool) Session::get('locale_preferred', false);

        if ($localePreferred && Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            $locale = $this->detectBrowserLanguage($request);
            Session::put('locale', $locale);
            Session::put('locale_preferred', false);
        }

        // Validate the locale and fall back to English.
        if (!in_array($locale, ['pt', 'en', 'es'])) {
            $locale = 'en';
            Session::put('locale', $locale);
            Session::put('locale_preferred', false);
        }

        // Set the application locale.
        App::setLocale($locale);
        app()->setLocale($locale);

        return $next($request);
    }
    
    private function detectBrowserLanguage(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');

        if ($acceptLanguage) {
            $languages = explode(',', $acceptLanguage);

            foreach ($languages as $language) {
                $locale = substr(trim($language), 0, 2);

                if ($locale === 'en') {
                    return 'en';
                }

                if ($locale === 'es') {
                    return 'es';
                }
            }
        }

        return 'en';
    }
}
