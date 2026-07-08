<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetContentTypes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $response = $next($request);
        $path = $request->path();

        if (str_ends_with($path, '.svg')) {
            return $next($request)->headers->set('Content-Type', 'image/svg+xml');
        } elseif (str_ends_with($path, '.ttf')) {
            return $next($request)->headers->set('Content-Type', 'font/ttf');
        } elseif (str_ends_with($path, '.otf')) {
            return $next($request)->headers->set('Content-Type', 'font/otf');
        } elseif (str_ends_with($path, '.woff')) {
            return $next($request)->headers->set('Content-Type', 'font/woff');
        } elseif (str_ends_with($path, '.woff2')) {
            return $next($request)->headers->set('Content-Type', 'font/woff2');
        } elseif (str_ends_with($path, '.png')) {
            return $next($request)->headers->set('Content-Type', 'image/png');
        }
        return $next($request);
    }
}
