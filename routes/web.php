<?php

use GeofDev\FilamentWeb3Auth\Http\Controllers\Web3AuthController;
use Illuminate\Support\Facades\Route;

$prefix = config('filament-web3-auth.route_prefix', 'web3-auth');
$middleware = config('filament-web3-auth.middleware', ['web']);
$authMiddleware = config('filament-web3-auth.auth_middleware', ['web', 'auth']);

// Public routes (signature request and login)
Route::middleware($middleware)
    ->prefix($prefix)
    ->group(function () {
        Route::get('/signature', [Web3AuthController::class, 'signature'])
            ->name('filament-web3-auth.signature');

        Route::post('/login', [Web3AuthController::class, 'login'])
            ->name('filament-web3-auth.login');
    });

// Authenticated routes (wallet linking/unlinking)
Route::middleware($authMiddleware)
    ->prefix($prefix)
    ->group(function () {
        Route::post('/wallet/link', [Web3AuthController::class, 'linkWallet'])
            ->name('filament-web3-auth.wallet.link');

        Route::post('/wallet/unlink', [Web3AuthController::class, 'unlinkWallet'])
            ->name('filament-web3-auth.wallet.unlink');
    });
