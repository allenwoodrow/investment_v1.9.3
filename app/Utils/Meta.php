<?php
namespace App\Utils;

use Currency\Util\CurrencySymbolUtil;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Exception;
use Illuminate\Support\Facades\Http;

class Meta {
    public static function money($value, $short = false,  $symbol = "USD") {
        $suffixes = ["", "K", "M", "B"];
        $suffixIndex = 0;
        $symbol = ($symbol != 'BTC') ? $symbol : 'USD';

        if($short) {
            while($value >= 1000 && $suffixIndex < count($suffixes) - 1) {
                $value /= 1000;
                $suffixIndex++;
            }
            return Meta::currency_symbol($symbol) . round($value, 2) . $suffixes[$suffixIndex];
        } else {
            return Meta::currency_symbol($symbol) . number_format($value, 2);
        }
    }

    public static function currency_symbol($code) {
        return CurrencySymbolUtil::getSymbol($code);
    }

    public static function vite_assets(): HtmlString
    {
        $devServerIsRunning = false;
     
        if (app()->environment('local')) {
            try {
                $server = Http::get("http://localhost:8080");
                if($server) {
                    $devServerIsRunning = true;
                }
            } catch (\Throwable $e) {
            }
        }
     
        if ($devServerIsRunning) {
    
            return new HtmlString(<<<HTML
                <script type="module" src="http://localhost:8080/@vite/client"></script>
                <script type="module" src="http://localhost:8080/resources/js/main.ts"></script>
            HTML);
        }
     
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $mainEntry = $manifest['resources/js/main.ts'];
        $css = $mainEntry['css'][0];
        $js = $mainEntry['file'];
        
        // Build CSS links
        $cssLinks = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/build/{$css}\">";
        
        // Add PrimeIcons if it's in the manifest
        if (isset($manifest['primeicons.css'])) {
            $primeiconsCss = $manifest['primeicons.css']['file'] ?? 'assets/primeicons-XXXXXXX.css';
            $cssLinks .= "\n            <link rel=\"stylesheet\" type=\"text/css\" href=\"/build/{$primeiconsCss}\">";
        } else {
            // Fallback to public primeicons.css
            $cssLinks .= "\n            <link rel=\"stylesheet\" type=\"text/css\" href=\"/primeicons.css\">";
        }
     
        return new HtmlString(<<<HTML
            {$cssLinks}
            <script type="module" src="/build/{$js}"></script>
        HTML);
    }    
}