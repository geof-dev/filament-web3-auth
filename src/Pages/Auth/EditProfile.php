<?php

namespace GeofDev\FilamentWeb3Auth\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
                $this->getWalletContentComponent(),
                ...\Illuminate\Support\Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
            ]);
    }

    protected function getWalletContentComponent(): Component
    {
        if (! config('filament-web3-auth.enabled', true)) {
            return Group::make([]);
        }

        return Section::make()
            ->heading(__('filament-web3-auth::messages.wallet_section'))
            ->schema([
                View::make('filament-web3-auth::components.wallet-manager'),
            ]);
    }
}
