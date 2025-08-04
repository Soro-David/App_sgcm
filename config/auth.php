<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'mairie' => [
            'driver' => 'session',
            'provider' => 'mairies',
        ],

        'agent' => [
            'driver' => 'session',
            'provider' => 'agents',
        ],

        // API guards
        'api-agent' => [
            'driver' => 'sanctum',
            'provider' => 'agents',
        ],

        'api-commercant' => [
            'driver' => 'sanctum',
            'provider' => 'commercants',
        ],

    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'mairies' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mairie::class,
        ],

        'agents' => [
            'driver' => 'eloquent',
            'model' => App\Models\Agent::class,
        ],

        'commercants' => [
            'driver' => 'eloquent',
            'model' => App\Models\Commercant::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],

        'mairies' => [
            'provider' => 'mairies',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],

        'agents' => [
            'provider' => 'agents',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],

        'commercants' => [
            'provider' => 'commercants',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
