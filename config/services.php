<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'salidas' => [
        'franco_de_honor' => env('PUNTAJE_FRANCO_DE_HONOR', 0),
        'franco_domingo' => [
            'min' => env('PUNTAJE_FRANCO_DOMINGO_MIN', 1),
            'max' => env('PUNTAJE_FRANCO_DOMINGO_MAX', 3),
        ],
        'franco_medio_domingo' => [
            'min' => env('PUNTAJE_FRANCO_MEDIO_DOMINGO_MIN', 4),
            'max' => env('PUNTAJE_FRANCO_MEDIO_DOMINGO_MAX', 5),
        ],
        'sin_salida' => env('PUNTAJE_SIN_SALIDA', 6),
    ],
];
