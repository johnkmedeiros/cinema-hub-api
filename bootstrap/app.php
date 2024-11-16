<?php

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
        $exceptions->render(function (RequestException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => '',
                    'status_code' => $e->response ? $e->response->status() : 500,
                ], $e->response ? $e->response->status() : 500);
            }
        });

        $exceptions->render(function (\Exception $e, Request $request) {
            if ($request->is('api/*') && $e instanceof \Exception && $e->getCode() === 500) {
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error',
                    'status_code' => 500,
                ], 500);
            }
        });
    })->create();
