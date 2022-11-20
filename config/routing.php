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

    'default' => 'discover',

    /*  
    |--------------------------------------------------------------------------
    | Routing Registrars
    |--------------------------------------------------------------------------
    |
    | Here you may define multiple instances of registrars with all different
    | kind of drivers.
    |
    | Drivers options: base, discover
    |
    */
    'registrars' => [
        'discover' => [
            'driver' => 'discover',
            'controllers' => [
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
        ]
    ]
];
