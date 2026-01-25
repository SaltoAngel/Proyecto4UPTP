<?php

use App\Http\Middleware\EnsureUserRoles;
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
    $middleware->alias([
        // Esto es lo único que REALMENTE necesitas para que tu controlador funcione
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
    ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->withProviders([
        \PHPJasper\PHPJasperServiceProvider::class,
    ])
    ->create();
