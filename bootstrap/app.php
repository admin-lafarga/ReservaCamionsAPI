<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // $middleware->append(StartSession::class);
        // $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Illuminate\Auth\AuthenticationException $e, $request) {
            // return response()->json([
            //     'message' => 'Ressource not found',
            // ], 404);
        });

        $exceptions->renderable(function (Symfony\Component\Routing\Exception\RouteNotFoundException $e, $request) {
            // return response()->json([
            //     'message' => 'Ressource not found',
            // ], 404);
        });
    })
    ->create();
