@if(config('filament-web3-auth.enabled', true))
<div class="mt-4">
    <button
        type="button"
        id="web3-connect-btn"
        class="fi-btn fi-btn-color-gray w-full inline-flex items-center justify-center gap-2"
    >
        <svg class="w-5 h-5" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M36.0112 3.00049L22.1427 13.3083L24.6497 7.30269L36.0112 3.00049Z" fill="#E17726"/>
            <path d="M3.98877 3.00049L17.7368 13.4076L15.3503 7.30269L3.98877 3.00049Z" fill="#E27625"/>
            <path d="M31.0012 27.2017L27.5008 32.4923L35.3003 34.6435L37.5374 27.3311L31.0012 27.2017Z" fill="#E27625"/>
            <path d="M2.4751 27.3311L4.69961 34.6435L12.4991 32.4923L8.99875 27.2017L2.4751 27.3311Z" fill="#E27625"/>
            <path d="M12.0615 17.4943L9.89941 20.7481L17.6239 21.1029L17.3615 12.7603L12.0615 17.4943Z" fill="#E27625"/>
            <path d="M27.9385 17.4943L22.5635 12.661L22.1427 21.1029L29.8797 20.7481L27.9385 17.4943Z" fill="#E27625"/>
            <path d="M12.4991 32.4924L17.1364 30.2305L13.1364 27.3809L12.4991 32.4924Z" fill="#E27625"/>
            <path d="M22.8635 30.2305L27.5008 32.4924L26.8635 27.3809L22.8635 30.2305Z" fill="#E27625"/>
            <path d="M27.5008 32.4923L22.8635 30.2305L23.2242 33.0052L23.1868 34.5439L27.5008 32.4923Z" fill="#D5BFB2"/>
            <path d="M12.4991 32.4923L16.8131 34.5439L16.7882 33.0052L17.1364 30.2305L12.4991 32.4923Z" fill="#D5BFB2"/>
            <path d="M16.8881 25.5048L13.0256 24.3696L15.7506 23.1099L16.8881 25.5048Z" fill="#233447"/>
            <path d="M23.1119 25.5048L24.2494 23.1099L26.9869 24.3696L23.1119 25.5048Z" fill="#233447"/>
            <path d="M12.4991 32.4924L13.1614 27.2017L8.99875 27.3311L12.4991 32.4924Z" fill="#CC6228"/>
            <path d="M26.8386 27.2017L27.5008 32.4924L31.0012 27.3311L26.8386 27.2017Z" fill="#CC6228"/>
            <path d="M29.8797 20.748L22.1427 21.1028L23.1119 25.5047L24.2494 23.1098L26.9869 24.3695L29.8797 20.748Z" fill="#CC6228"/>
            <path d="M13.0256 24.3695L15.7506 23.1098L16.8881 25.5047L17.6239 21.1028L9.89941 20.748L13.0256 24.3695Z" fill="#CC6228"/>
            <path d="M9.89941 20.748L13.1364 27.3808L13.0256 24.3695L9.89941 20.748Z" fill="#E27525"/>
            <path d="M26.9869 24.3695L26.8635 27.3808L29.8797 20.748L26.9869 24.3695Z" fill="#E27525"/>
            <path d="M17.6239 21.1029L16.8881 25.5048L17.7988 30.0064L18.0113 23.7092L17.6239 21.1029Z" fill="#E27525"/>
            <path d="M22.1427 21.1029L21.7677 23.6967L21.9552 30.0064L22.8759 25.5048L22.1427 21.1029Z" fill="#E27525"/>
            <path d="M23.1119 25.5047L22.876 30.0063L23.2242 30.2304L26.8636 27.3808L26.987 24.3695L23.1119 25.5047Z" fill="#F5841F"/>
            <path d="M13.0256 24.3695L13.1364 27.3808L16.7758 30.2304L17.124 30.0063L16.8881 25.5047L13.0256 24.3695Z" fill="#F5841F"/>
            <path d="M23.1868 34.5439L23.2242 33.0052L22.8885 32.7064H17.1115L16.7882 33.0052L16.8131 34.5439L12.4991 32.4924L13.9241 33.6774L17.0615 35.8287H22.9385L26.0759 33.6774L27.5008 32.4924L23.1868 34.5439Z" fill="#C0AC9D"/>
            <path d="M22.8635 30.2305L22.5153 30.0063H17.4847L17.1364 30.2305L16.7882 33.0052L17.1115 32.7063H22.8885L23.2242 33.0052L22.8635 30.2305Z" fill="#161616"/>
            <path d="M36.6112 14.0572L37.7737 8.42914L36.0112 3.00049L22.8635 12.9587L27.9385 17.4943L35.0628 19.5709L36.6862 17.6812L35.9615 17.1568L37.0865 16.1336L36.2118 15.4673L37.3368 14.619L36.6112 14.0572Z" fill="#763E1A"/>
            <path d="M2.22632 8.42914L3.38882 14.0572L2.65069 14.619L3.78819 15.4673L2.91319 16.1336L4.03819 17.1568L3.31319 17.6812L4.93694 19.5709L12.0612 17.4943L17.1362 12.9587L3.98877 3.00049L2.22632 8.42914Z" fill="#763E1A"/>
            <path d="M35.0628 19.5709L27.9385 17.4943L29.8797 20.748L26.8635 27.3808L31.0012 27.3311H37.5374L35.0628 19.5709Z" fill="#F5841F"/>
            <path d="M12.0612 17.4943L4.93694 19.5709L2.4751 27.3311H8.99875L13.1364 27.3808L9.89941 20.748L12.0612 17.4943Z" fill="#F5841F"/>
            <path d="M22.1427 21.1029L22.8635 12.9587L24.6498 7.30273H15.3503L17.1365 12.9587L17.6239 21.1029L17.7864 23.7217L17.7988 30.0064H21.9552L21.9802 23.7217L22.1427 21.1029Z" fill="#F5841F"/>
        </svg>
        <span id="web3-btn-text">{{ __('filament-web3-auth::messages.connect_with_wallet') }}</span>
    </button>

    <p id="web3-error" class="mt-2 text-sm text-danger-600 dark:text-danger-400 hidden"></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('web3-connect-btn');
    const btnText = document.getElementById('web3-btn-text');
    const errorEl = document.getElementById('web3-error');

    function showError(message) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    function hideError() {
        errorEl.classList.add('hidden');
    }

    function setLoading(loading) {
        btn.disabled = loading;
        btnText.textContent = loading
            ? '{{ __("filament-web3-auth::messages.connecting") }}'
            : '{{ __("filament-web3-auth::messages.connect_with_wallet") }}';
    }

    btn.addEventListener('click', async function() {
        hideError();

        if (typeof window.ethereum === 'undefined') {
            showError('{{ __("filament-web3-auth::messages.wallet_not_installed") }}');
            return;
        }

        try {
            setLoading(true);

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

            const loginResponse = await fetch('{{ route("filament-web3-auth.login") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ address, signature })
            });

            const result = await loginResponse.json();

            if (result.success) {
                window.location.href = result.redirect;
            } else {
                showError(result.error || '{{ __("filament-web3-auth::messages.authentication_failed") }}');
            }
        } catch (error) {
            console.error('Web3 login error:', error);
            if (error.code === 4001) {
                showError('{{ __("filament-web3-auth::messages.signature_rejected") }}');
            } else {
                showError(error.message || '{{ __("filament-web3-auth::messages.auth_error") }}');
            }
        } finally {
            setLoading(false);
        }
    });
});
</script>
@endif
