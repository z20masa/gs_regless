<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ここに追記
        $middleware->redirectTo(
            guests: '/login',
            users: '/dashboard', // ログイン済みユーザーの遷移先
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();