@php
    $user = auth()->user();
    $hasWallet = !empty($user->eth_address);
@endphp

<div id="wallet-manager">
    @if($hasWallet)
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-success-100 dark:bg-success-500/20">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-success-600 dark:text-success-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-950 dark:text-white">
                        {{ __('filament-web3-auth::messages.wallet_connected') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                        {{ substr($user->eth_address, 0, 6) }}...{{ substr($user->eth_address, -4) }}
                    </p>
                </div>
            </div>
            <x-filament::button
                type="button"
                id="disconnect-wallet-btn"
                color="danger"
                size="sm"
            >
                {{ __('filament-web3-auth::messages.disconnect_wallet') }}
            </x-filament::button>
        </div>
    @else
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-white/10">
                    <x-heroicon-o-wallet class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-950 dark:text-white">
                        {{ __('filament-web3-auth::messages.no_wallet_connected') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('filament-web3-auth::messages.connect_wallet_description') }}
                    </p>
                </div>
            </div>
            <x-filament::button
                type="button"
                id="connect-wallet-btn"
                color="primary"
                size="sm"
            >
                {{ __('filament-web3-auth::messages.connect_wallet_btn') }}
            </x-filament::button>
        </div>
    @endif

    <p id="wallet-error" class="mt-2 text-sm text-danger-600 dark:text-danger-400 hidden"></p>
    <p id="wallet-success" class="mt-2 text-sm text-success-600 dark:text-success-400 hidden"></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const errorEl = document.getElementById('wallet-error');
    const successEl = document.getElementById('wallet-success');
    const connectBtn = document.getElementById('connect-wallet-btn');
    const disconnectBtn = document.getElementById('disconnect-wallet-btn');

    function showError(message) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
        successEl.classList.add('hidden');
    }

    function showSuccess(message) {
        successEl.textContent = message;
        successEl.classList.remove('hidden');
        errorEl.classList.add('hidden');
    }

    function hideMessages() {
        errorEl.classList.add('hidden');
        successEl.classList.add('hidden');
    }

    if (connectBtn) {
        connectBtn.addEventListener('click', async function() {
            hideMessages();

            if (typeof window.ethereum === 'undefined') {
                showError('{{ __("filament-web3-auth::messages.wallet_not_installed") }}');
                return;
            }

            try {
                connectBtn.disabled = true;
                connectBtn.textContent = '{{ __("filament-web3-auth::messages.connecting") }}';

                const provider = new ethers.BrowserProvider(window.ethereum);
                const signer = await provider.getSigner();
                const address = await signer.getAddress();

                const signatureResponse = await fetch('{{ route("filament-web3-auth.signature") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                });

                if (!signatureResponse.ok) {
                    throw new Error('{{ __("filament-web3-auth::messages.failed_to_get_signature") }}');
                }

                const { message } = await signatureResponse.json();
                const signature = await signer.signMessage(message);

                const linkResponse = await fetch('{{ route("filament-web3-auth.wallet.link") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ address, signature })
                });

                const result = await linkResponse.json();

                if (result.success) {
                    showSuccess(result.message);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showError(result.error || '{{ __("filament-web3-auth::messages.wallet_link_failed") }}');
                }
            } catch (error) {
                console.error('Wallet link error:', error);
                if (error.code === 4001) {
                    showError('{{ __("filament-web3-auth::messages.signature_rejected") }}');
                } else {
                    showError(error.message || '{{ __("filament-web3-auth::messages.auth_error") }}');
                }
            } finally {
                connectBtn.disabled = false;
                connectBtn.textContent = '{{ __("filament-web3-auth::messages.connect_wallet_btn") }}';
            }
        });
    }

    if (disconnectBtn) {
        disconnectBtn.addEventListener('click', async function() {
            if (!confirm('{{ __("filament-web3-auth::messages.confirm_disconnect") }}')) {
                return;
            }

            hideMessages();

            if (typeof window.ethereum === 'undefined') {
                showError('{{ __("filament-web3-auth::messages.wallet_not_installed") }}');
                return;
            }

            try {
                disconnectBtn.disabled = true;

                const provider = new ethers.BrowserProvider(window.ethereum);
                const signer = await provider.getSigner();
                const address = await signer.getAddress();

                const signatureResponse = await fetch('{{ route("filament-web3-auth.signature") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                });

                if (!signatureResponse.ok) {
                    throw new Error('{{ __("filament-web3-auth::messages.failed_to_get_signature") }}');
                }

                const { message } = await signatureResponse.json();
                const signature = await signer.signMessage(message);

                const response = await fetch('{{ route("filament-web3-auth.wallet.unlink") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ address, signature })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(result.message);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showError(result.error || '{{ __("filament-web3-auth::messages.wallet_unlink_failed") }}');
                }
            } catch (error) {
                console.error('Wallet unlink error:', error);
                if (error.code === 4001) {
                    showError('{{ __("filament-web3-auth::messages.signature_rejected") }}');
                } else {
                    showError(error.message || '{{ __("filament-web3-auth::messages.auth_error") }}');
                }
            } finally {
                disconnectBtn.disabled = false;
            }
        });
    }
});
</script>
