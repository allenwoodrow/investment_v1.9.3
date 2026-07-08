<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request)
        ->header('Access-Control-Allow-Origin', env('APP_URL', '*'))
        ->header('Access-Control-Allow-Credentials', true)
        ->header('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS, POST, PUT')
        ->header('Access-Control-Max-Age', '3600')
        ->header('Access-Control-Allow-Headers', 'Origin, Accept, Content-Type, X-Requested-With, Authorization');
    }
}
