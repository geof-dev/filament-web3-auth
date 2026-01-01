<?php

namespace GeofDev\FilamentWeb3Auth\Http\Controllers;

use Elliptic\EC;
use Filament\Facades\Filament;
use GeofDev\FilamentWeb3Auth\FilamentWeb3AuthPlugin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class Web3AuthController extends Controller
{
    public function signature(Request $request): JsonResponse
    {
        $nonce = Str::random(32);
        $request->session()->put('web3_auth_nonce', $nonce);

        $message = $this->buildSignatureMessage($nonce);

        return response()->json([
            'message' => $message,
            'nonce' => $nonce,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'address' => 'required|string|size:42',
            'signature' => 'required|string',
        ]);

        $address = $request->input('address');
        $signature = $request->input('signature');
        $nonce = $request->session()->get('web3_auth_nonce');

        if (! $nonce) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.session_expired'),
            ], 422);
        }

        $message = $this->buildSignatureMessage($nonce);

        if (! $this->verifySignature($message, $signature, $address)) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.invalid_signature'),
            ], 422);
        }

        $request->session()->forget('web3_auth_nonce');

        $userModel = $this->getUserModel();
        $user = $userModel::where('eth_address', strtolower($address))->first();

        if (! $user && $this->shouldAutoRegister()) {
            $user = $userModel::create([
                'name' => $this->shortenAddress($address),
                'email' => strtolower($address) . '@wallet.local',
                'eth_address' => strtolower($address),
                'password' => bcrypt(Str::random(32)),
            ]);
        }

        if (! $user) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.no_account_found'),
            ], 422);
        }

        Filament::auth()->login($user, remember: true);

        $panel = Filament::getCurrentPanel();
        $redirectUrl = $panel?->getUrl() ?? url('/admin');

        return response()->json([
            'success' => true,
            'redirect' => $redirectUrl,
        ]);
    }

    public function linkWallet(Request $request): JsonResponse
    {
        $request->validate([
            'address' => 'required|string|size:42',
            'signature' => 'required|string',
        ]);

        $user = $request->user();
        $address = $request->input('address');
        $signature = $request->input('signature');
        $nonce = $request->session()->get('web3_auth_nonce');

        if (! $nonce) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.session_expired'),
            ], 422);
        }

        $message = $this->buildSignatureMessage($nonce);

        if (! $this->verifySignature($message, $signature, $address)) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.invalid_signature'),
            ], 422);
        }

        $request->session()->forget('web3_auth_nonce');

        $userModel = $this->getUserModel();
        $existingUser = $userModel::where('eth_address', strtolower($address))
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.wallet_already_linked'),
            ], 422);
        }

        $user->update(['eth_address' => strtolower($address)]);

        return response()->json([
            'success' => true,
            'message' => __('filament-web3-auth::messages.wallet_linked_success'),
        ]);
    }

    public function unlinkWallet(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->eth_address) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.no_wallet_to_unlink'),
            ], 422);
        }

        if (str_ends_with($user->email, '@wallet.local')) {
            return response()->json([
                'error' => __('filament-web3-auth::messages.cannot_unlink_primary'),
            ], 422);
        }

        $user->update(['eth_address' => null]);

        return response()->json([
            'success' => true,
            'message' => __('filament-web3-auth::messages.wallet_unlinked_success'),
        ]);
    }

    protected function buildSignatureMessage(string $nonce): string
    {
        $plugin = app(FilamentWeb3AuthPlugin::class);
        $customMessage = $plugin->getSignatureMessage();

        $template = $customMessage ?? config(
            'filament-web3-auth.signature_message',
            'Sign this message to authenticate with :app_name. Nonce: :nonce'
        );

        return str_replace(
            [':app_name', ':nonce'],
            [config('app.name'), $nonce],
            $template
        );
    }

    protected function verifySignature(string $message, string $signature, string $address): bool
    {
        try {
            $msglen = strlen($message);
            $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$msglen}{$message}", 256);
            $sign = ['r' => substr($signature, 2, 64), 's' => substr($signature, 66, 64)];
            $recid = ord(hex2bin(substr($signature, 130, 2))) - 27;

            if ($recid !== ($recid & 1)) {
                return false;
            }

            $ec = new EC('secp256k1');
            $pubkey = $ec->recoverPubKey($hash, $sign, $recid);

            $recoveredAddress = '0x' . substr(
                Keccak::hash(substr(hex2bin($pubkey->encode('hex')), 1), 256),
                24
            );

            return strtolower($recoveredAddress) === strtolower($address);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function shortenAddress(string $address): string
    {
        return substr($address, 0, 6) . '...' . substr($address, -4);
    }

    protected function getUserModel(): string
    {
        return config('filament-web3-auth.user_model')
            ?? config('auth.providers.users.model')
            ?? \App\Models\User::class;
    }

    protected function shouldAutoRegister(): bool
    {
        $plugin = app(FilamentWeb3AuthPlugin::class);

        return $plugin->shouldAutoRegister()
            && config('filament-web3-auth.auto_register', true);
    }
}
