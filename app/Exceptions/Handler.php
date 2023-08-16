<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            } else if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json(['message' => $e->getMessage()], 422);
            } else if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['message' => 'Not Found'], 404);
            } else if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json(['message' => 'Not Found'], 404);
            } else if ($e instanceof \Illuminate\Database\QueryException) {
                return response()->json(['message' => 'Internal Server Error'], 500);
            } else if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                return response()->json(['message' => 'Token Mismatch'], 419);
            } else if ($e instanceof \Illuminate\Validation\UnauthorizedException) {
                return response()->json(['message' => 'Unauthorized'], 401);
            } else if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json(['message' => 'Forbidden'], 403);
            } else if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return response()->json(['message' => 'Method Not Allowed'], 405);
            } else if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return response()->json(['message' => 'Internal Server Error'], 500);
            }
        });
    }


    protected function shouldReturnJson($request, Throwable $e)
    {
        return true;
    }
}
