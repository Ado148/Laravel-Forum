<?php

use App\Http\Middleware\CanViewPostMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['can-view-post' => CanViewPostMiddleware::class]); // Register custom middleware alias
        $middleware->alias(['is_admin' => \App\Http\Middleware\IsAdminMiddleware::class]); // Register custom middleware alias for admin check
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
