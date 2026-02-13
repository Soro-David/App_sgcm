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

        'finance' => [
            'driver' => 'session',
            'provider' => 'finances',
        ],

        'commercant' => [
            'driver' => 'session',
            'provider' => 'commercants',
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

        'financier' => [
            'driver' => 'session',
            'provider' => 'financiers',
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

        'finances' => [
            'driver' => 'eloquent',
            'model' => App\Models\Finance::class,
        ],

        'commercants' => [
            'driver' => 'eloquent',
            'model' => App\Models\Commercant::class,
        ],

        'agents' => [
            'driver' => 'eloquent',
            'model' => App\Models\Agent::class,
        ],

        'financiers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Financier::class,
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

        'finances' => [
            'provider' => 'finances',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],

        'commercants' => [
            'provider' => 'commercants',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],

        'agents' => [
            'provider' => 'agents',
            'table' => 'password_reset_tokens',
            'expire' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
