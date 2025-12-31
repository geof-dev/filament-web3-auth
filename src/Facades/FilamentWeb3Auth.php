<?php

namespace GeofDev\FilamentWeb3Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GeofDev\FilamentWeb3Auth\FilamentWeb3AuthPlugin
 */
class FilamentWeb3Auth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GeofDev\FilamentWeb3Auth\FilamentWeb3AuthPlugin::class;
    }
}
