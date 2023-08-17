<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        AuthenticationException::class => \Psr\Log\LogLevel::INFO,
        AuthorizationException::class => \Psr\Log\LogLevel::INFO,
        ModelNotFoundException::class => \Psr\Log\LogLevel::INFO,
        \Illuminate\Session\TokenMismatchException::class => \Psr\Log\LogLevel::INFO,
        \Illuminate\Validation\ValidationException::class => \Psr\Log\LogLevel::INFO,
        \Symfony\Component\HttpKernel\Exception\HttpException::class => \Psr\Log\LogLevel::INFO,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => \Psr\Log\LogLevel::INFO,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => \Psr\Log\LogLevel::INFO,
        \Illuminate\Database\QueryException::class => \Psr\Log\LogLevel::INFO,
        \Illuminate\Validation\UnauthorizedException::class => \Psr\Log\LogLevel::INFO,
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Illuminate\Database\QueryException::class,
        \Illuminate\Validation\UnauthorizedException::class,
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

            if ($e instanceof AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            } else if ($e instanceof ValidationException) {
                return response()->json(['message' => $e->getMessage()], 422);
            } else if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Not Found'], 404);
            } else if ($e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Not Found'], 404);
            } else if ($e instanceof \Illuminate\Database\QueryException) {
                return response()->json(['message' => 'Internal Server Error'], 500);
            } else if ($e instanceof TokenMismatchException) {
                return response()->json(['message' => 'Token Mismatch'], 419);
            } else if ($e instanceof UnauthorizedException) {
                return response()->json(['message' => 'Unauthorized'], 401);
            } else if ($e instanceof AuthorizationException) {
                return response()->json(['message' => 'Forbidden'], 403);
            } else if ($e instanceof MethodNotAllowedHttpException) {
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
