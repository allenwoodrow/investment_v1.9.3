<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Exception $exception)
    // {
    //     if ($exception instanceof TokenExpiredException) {
    //         return response()->json(['error' => 'Token is expired'], 401);
    //     } elseif ($exception instanceof TokenInvalidException) {
    //         return response()->json(['error' => 'Token is invalid'], 401);
    //     } elseif ($exception instanceof JWTException) {
    //         return response()->json(['error' => 'There was an issue with the token'], 401);
    //     }

    //     return parent::render($request, $exception);
    // }
    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => 'You are not allowed to access this endpoint'], Response::HTTP_FORBIDDEN);
    //     }

    //     return redirect()->guest($exception->redirectTo() ?? route('web.login'));
    // }
}
