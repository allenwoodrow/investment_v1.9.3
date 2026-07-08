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
        // Check if user has explicitly chosen a language
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            // No choice made yet - detect from browser or default to Portuguese
            $locale = $this->detectBrowserLanguage($request);
            Session::put('locale', $locale);
        }
        
        // Validate the locale
        if (!in_array($locale, ['pt', 'en', 'es'])) {
            $locale = 'pt';
            Session::put('locale', $locale);
        }
        
        // Set the application locale
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
                
                if ($locale === 'pt') {
                    return 'pt';
                }
                
                if ($locale === 'en') {
                    return 'en';
                }

                if ($locale === 'es') {
                    return 'es';
                }
            }
        }
        
        return 'pt'; // Default to Portuguese
    }
}
