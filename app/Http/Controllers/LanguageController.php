<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $locale = $request->input('locale');
        
        if (in_array($locale, ['pt', 'en', 'es'])) {
            // Store in session
            Session::put('locale', $locale);
            
            // Immediately apply the locale
            App::setLocale($locale);
            app()->setLocale($locale);
            
            // Force save the session
            Session::save();
        }
        
        return redirect()->back();
    }
    
    public function switchAjax(Request $request)
    {
        $locale = $request->input('locale');
        
        if (in_array($locale, ['pt', 'en', 'es'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
            app()->setLocale($locale);
            Session::save();
            
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => match ($locale) {
                    'default' => 'Idioma alterado com sucesso',
                    'es' => 'Idioma cambiado correctamente',
                    'en' => 'Language switched successfully',
                }
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Idioma inválido'
        ], 400);
    }
    
    public function getCurrentLocale()
    {
        return response()->json([
            'locale' => App::getLocale(),
            'session_locale' => Session::get('locale')
        ]);
    }
}
