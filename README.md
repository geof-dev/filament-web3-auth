# Filament Web3 Auth

[![Latest Version on Packagist](https://img.shields.io/packagist/v/geof-dev/filament-web3-auth.svg?style=flat-square)](https://packagist.org/packages/geof-dev/filament-web3-auth)
[![Total Downloads](https://img.shields.io/packagist/dt/geof-dev/filament-web3-auth.svg?style=flat-square)](https://packagist.org/packages/geof-dev/filament-web3-auth)

A Filament v4 plugin for Web3 wallet authentication (MetaMask, and other EIP-1193 compatible wallets).

<p align="center">
    <img src="art/banner.jpeg" alt="Filament Web3 Auth" style="width:100%; max-width:800px;">
</p>

## Features

- Login with crypto wallet (MetaMask, etc.)
- Auto-register new users on first wallet login
- Link/unlink wallet to existing accounts
- Multi-network token dashboard (Ethereum, Polygon, Arbitrum, Base, Sepolia)
- Fully customizable and translatable

## Installation

Install the package via Composer:

```bash
composer require geof-dev/filament-web3-auth
```

Publish and run the migration:

```bash
php artisan vendor:publish --tag="filament-web3-auth-migrations"
php artisan migrate
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="filament-web3-auth-config"
```

Optionally publish the views:

```bash
php artisan vendor:publish --tag="filament-web3-auth-views"
```

Add the plugin's views to your Filament theme CSS file (e.g., `resources/css/filament/admin/theme.css`):

```css
@source '../../../../vendor/geof-dev/filament-web3-auth/resources/views/**/*.blade.php';
```

Then rebuild your assets:

```bash
npm run build
```

## Usage

Register the plugin in your Filament panel provider:

```php
use GeofDev\FilamentWeb3Auth\FilamentWeb3AuthPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(
            FilamentWeb3AuthPlugin::make()
                ->autoRegister(true)      // Auto-create users on first login
                ->showOnLogin(true)       // Show button on login page
        );
}
```

## Plugin Options

| Method | Description | Default |
|--------|-------------|---------|
| `autoRegister(bool)` | Auto-create users on wallet login | `false` |
| `showOnLogin(bool)` | Show connect button on login page | `true` |
| `signatureMessage(string)` | Custom signature message template | Config value |

## Configuration

```php
// config/filament-web3-auth.php

return [
    'enabled' => env('FILAMENT_WEB3_AUTH_ENABLED', true),
    'auto_register' => env('FILAMENT_WEB3_AUTH_AUTO_REGISTER', true),
    'signature_message' => 'Sign this message to authenticate with :app_name. Nonce: :nonce',
    'user_model' => null, // Uses default auth model
    'route_prefix' => 'web3-auth',
    'middleware' => ['web'],
    'auth_middleware' => ['web', 'auth'],
];
```

## User Model Setup

Ensure your User model has the `eth_address` column fillable:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'eth_address',
];
```

## Adding Wallet Manager to Profile

The easiest way is to use the plugin's EditProfile page in your panel:

```php
use GeofDev\FilamentWeb3Auth\Pages\Auth\EditProfile;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->profile(EditProfile::class);
}
```

Or add the component manually to your custom profile page:

```php
use Filament\Schemas\Components\View;

View::make('filament-web3-auth::components.wallet-manager')
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Geoffrey B.](https://github.com/geof-dev)
- [All Contributors](../../contributors)

## License


The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
