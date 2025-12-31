<?php

namespace GeofDev\FilamentWeb3Auth;

use GeofDev\FilamentWeb3Auth\Http\Controllers\Web3AuthController;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentWeb3AuthServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-web3-auth';

    public static string $viewNamespace = 'filament-web3-auth';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasTranslations()
            ->hasMigration('add_eth_address_to_users_table')
            ->hasRoute('web');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilamentWeb3AuthPlugin::class);
    }

    public function packageBooted(): void
    {
        //
    }
}
