<?php

use App\Application\Exceptions\BusinessException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../app/Infrastructure/routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (BusinessException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => $e->getErrorCode(),
                ], $e->getCode() ?: 400);
            }
        });

        $exceptions->render(function (\Exception $e, Request $request) {
            if ($request->is('api/*') && $e instanceof \Exception && $e->getCode() === 500) {
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error',
                    'error_code' => null,
                ], 500);
            }
        });
    })->create();
