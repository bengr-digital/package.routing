<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Routing Registrar
    |--------------------------------------------------------------------------
    |
    | Default registrar that will be automatically used when using routing
    | library.
    |
    */

    'default' => 'livewire',

    /*  
    |--------------------------------------------------------------------------
    | Routing Registrars
    |--------------------------------------------------------------------------
    |
    | Here you may define multiple instances of registrars with all different
    | kind of drivers.
    |
    | Supported drivers: "discover", "base"
    |
    */
    'registrars' => [
        'discover' => [
            'driver' => 'discover',
            'paths' => [
                app_path('Http/Controllers')
            ],
            'middleware' => ['web'],
            'transformers' => [
                Bengr\Routing\Transformers\DeleteDefaultControllerMethodRoutes::class,
                Bengr\Routing\Transformers\HandlePrefixAttribute::class,
                Bengr\Routing\Transformers\AddControllerUriToActions::class,
                Bengr\Routing\Transformers\HandleNoDiscoverAttribute::class,
                Bengr\Routing\Transformers\HandleRouteNameAttribute::class,
                Bengr\Routing\Transformers\HandleMiddlewareAttribute::class,
                Bengr\Routing\Transformers\HandleHttpMethodsAttribute::class,
                Bengr\Routing\Transformers\HandleUriAttribute::class,
                Bengr\Routing\Transformers\HandleFullUriAttribute::class,
                Bengr\Routing\Transformers\HandleWheresAttribute::class,
                Bengr\Routing\Transformers\AddDefaultRouteName::class,
                Bengr\Routing\Transformers\HandleDomainAttribute::class,
            ]
        ],
        'livewire' => [
            'driver' => 'livewire',
            'paths' => [
                app_path('Http/Livewire')
            ],
            'middleware' => ['web'],
            'transformers' => [
                Bengr\Routing\Registrars\Livewire\Transformers\HandleNoDiscoverAttribute::class,
                Bengr\Routing\Registrars\Livewire\Transformers\HandleUriAttribute::class,
                Bengr\Routing\Registrars\Livewire\Transformers\HandleRouteNameAttribute::class,
                Bengr\Routing\Registrars\Livewire\Transformers\HandleMiddlewareAttribute::class,
                Bengr\Routing\Registrars\Livewire\Transformers\HandleHttpMethodsAttribute::class,
            ]
        ],
        'base' => [
            'driver' => 'base',
            'groups' => [
                [
                    'prefix' => 'api',
                    'middleware' => ['api'],
                    'file' => base_path('routes/api.php')
                ],
                [
                    'middleware' => ['web'],
                    'file' => base_path('routes/web.php')
                ]
            ]
        ]
    ]
];
