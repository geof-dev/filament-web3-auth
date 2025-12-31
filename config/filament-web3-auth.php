<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Web3 Authentication
    |--------------------------------------------------------------------------
    |
    | This option controls whether the Web3 authentication feature is enabled.
    | When disabled, the connect button will not be shown on the login page.
    |
    */
    'enabled' => env('FILAMENT_WEB3_AUTH_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Auto Register Users
    |--------------------------------------------------------------------------
    |
    | When enabled, new users will be automatically created when they
    | authenticate with a wallet address that doesn't exist in the database.
    |
    */
    'auto_register' => env('FILAMENT_WEB3_AUTH_AUTO_REGISTER', true),

    /*
    |--------------------------------------------------------------------------
    | Signature Message Template
    |--------------------------------------------------------------------------
    |
    | The message that users will sign to authenticate. You can use the
    | following placeholders: :app_name, :nonce
    |
    */
    'signature_message' => env(
        'FILAMENT_WEB3_AUTH_SIGNATURE_MESSAGE',
        'Sign this message to authenticate with :app_name. Nonce: :nonce'
    ),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The Eloquent model used for users. This model must have an 'eth_address'
    | column. If not specified, it will use the default auth user model.
    |
    */
    'user_model' => null,

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix for all Web3 auth routes. By default, routes will be
    | registered under /web3-auth/*
    |
    */
    'route_prefix' => env('FILAMENT_WEB3_AUTH_ROUTE_PREFIX', 'web3-auth'),

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | The middleware to apply to the Web3 auth routes.
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Authenticated Middleware
    |--------------------------------------------------------------------------
    |
    | The middleware to apply to routes that require authentication
    | (like wallet linking/unlinking).
    |
    */
    'auth_middleware' => ['web', 'auth'],
];
