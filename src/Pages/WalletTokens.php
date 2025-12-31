<?php

namespace GeofDev\FilamentWeb3Auth\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class WalletTokens extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wallet';

    protected static ?int $navigationSort = 100;

    public static function canAccess(): bool
    {
        if (! config('filament-web3-auth.enabled', true)) {
            return false;
        }

        $user = auth()->user();

        return $user && ! empty($user->eth_address);
    }

    public static function shouldRegisterNavigation(): bool
    {
        if (! config('filament-web3-auth.enabled', true)) {
            return false;
        }

        $user = auth()->user();

        return $user && ! empty($user->eth_address);
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-web3-auth::wallet.navigation_label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament-web3-auth::wallet.page_title');
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public function getEthAddress(): ?string
    {
        return auth()->user()?->eth_address;
    }

    public function getView(): string
    {
        return 'filament-web3-auth::pages.wallet-tokens';
    }
}
