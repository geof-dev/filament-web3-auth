<?php

namespace GeofDev\FilamentWeb3Auth;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class FilamentWeb3AuthPlugin implements Plugin
{
    protected bool $autoRegister = true;

    protected bool $showOnLogin = true;

    protected ?string $signatureMessage = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-web3-auth';
    }

    public function autoRegister(bool $condition = true): static
    {
        $this->autoRegister = $condition;

        return $this;
    }

    public function showOnLogin(bool $condition = true): static
    {
        $this->showOnLogin = $condition;

        return $this;
    }

    public function signatureMessage(string $message): static
    {
        $this->signatureMessage = $message;

        return $this;
    }

    public function shouldAutoRegister(): bool
    {
        return $this->autoRegister;
    }

    public function shouldShowOnLogin(): bool
    {
        return $this->showOnLogin;
    }

    public function getSignatureMessage(): ?string
    {
        return $this->signatureMessage;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        if ($this->showOnLogin) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => Blade::render("@include('filament-web3-auth::components.connect-button')"),
            );
        }
    }
}
