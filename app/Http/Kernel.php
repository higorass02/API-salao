<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Middlewares globais
    protected $middleware = [
        // você pode deixar vazio ou adicionar logging, CORS, etc.
    ];

    // Grupos de middleware
    protected $middlewareGroups = [
        'api' => [
            // Middleware de throttle para limitar requisições
            'throttle:api',
            // Bindings automáticos de rotas
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Sanctum token
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ],
    ];

    // Middleware individuais para rotas
    protected $routeMiddleware = [
        'auth' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ];
}
