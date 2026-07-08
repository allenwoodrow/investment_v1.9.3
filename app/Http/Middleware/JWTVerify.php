<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;
use App\Services\JWTService;
use App\Models\TokenBlacklist;


class JWTVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $publicHelper = new JWTService;

        try {
            // $jwt = $publicHelper->GetRawJWT();
            // // $string)
            // if ($jwt && TokenBlacklist::where('token_id', base64_encode($jwt))->exists()) {
            //     return response()->json(['error' => 'Token revoked'], 401);
            // }
            $token = $publicHelper->GetAndDecodeJWT();

            // do further JWT verification here

            
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
