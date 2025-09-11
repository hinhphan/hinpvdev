<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use App\Enums\ResponseCode;
use App\Helpers\RespondHepler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Route API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // For API
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*') || $request->is('pi')) {
                $code = ResponseCode::INTERNAL_SERVER_ERROR;
                $message = __('messages.' . ResponseCode::INTERNAL_SERVER_ERROR);
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $errors = [];

                // MethodNotAllowedHttpException
                if ($e instanceof MethodNotAllowedHttpException) {
                    $code = ResponseCode::METHOD_NOT_ALLOWED;
                    $message = __('messages.' . ResponseCode::METHOD_NOT_ALLOWED);
                    $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
                }

                // NotFoundHttpException
                if ($e instanceof NotFoundHttpException) {
                    $code = ResponseCode::NOT_FOUND;
                    $message = __('messages.' . ResponseCode::NOT_FOUND);
                    $statusCode = Response::HTTP_NOT_FOUND;
                }

                // ValidationException
                if ($e instanceof ValidationException) {
                    $code = ResponseCode::UNPROCESSABLE_ENTITY;
                    $message = __('messages.' . ResponseCode::BAD_REQUEST);
                    $errors = $e->validator->errors()->toArray();
                    $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                }

                // AuthenticationException
                if ($e instanceof AuthenticationException) {
                    $code = ResponseCode::UNAUTHORIZED;
                    $message = __('messages.' . ResponseCode::UNAUTHORIZED);
                    $statusCode = Response::HTTP_UNAUTHORIZED;
                }

                // Global Log
                Log::error($e);

                return RespondHepler::formatJsonResponseData($code, $message, $statusCode, [], $errors);
            }
        });
    })->create();
