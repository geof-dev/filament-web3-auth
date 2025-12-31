<x-filament-panels::page>
    <div class="space-y-6" id="wallet-tokens-container">
        <x-filament::section>
            <x-slot name="heading">
                {{ __('filament-web3-auth::wallet.your_wallet') }}
            </x-slot>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900">
                    <x-heroicon-o-wallet class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('filament-web3-auth::wallet.address') }}</p>
                    <p class="font-mono text-sm text-gray-900 dark:text-white break-all">{{ $this->getEthAddress() }}</p>
                </div>
                <div class="ml-auto">
                    <button
                        type="button"
                        onclick="navigator.clipboard.writeText('{{ $this->getEthAddress() }}')"
                        class="fi-btn fi-btn-size-sm fi-btn-color-gray rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20"
                    >
                        <x-heroicon-s-clipboard class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                {{ __('filament-web3-auth::wallet.network') }}
            </x-slot>

            <div class="flex gap-2 flex-wrap">
                <button type="button" data-network="mainnet" class="network-btn fi-btn fi-btn-size-sm rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20 bg-primary-600 text-white">
                    Ethereum
                </button>
                <button type="button" data-network="polygon" class="network-btn fi-btn fi-btn-size-sm rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    Polygon
                </button>
                <button type="button" data-network="arbitrum" class="network-btn fi-btn fi-btn-size-sm rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    Arbitrum
                </button>
                <button type="button" data-network="base" class="network-btn fi-btn fi-btn-size-sm rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    Base
                </button>
                <button type="button" data-network="sepolia" class="network-btn fi-btn fi-btn-size-sm rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    Sepolia
                </button>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                <span id="native-token-name">ETH</span> {{ __('filament-web3-auth::wallet.balance') }}
            </x-slot>

            <div id="balance-loading" class="flex items-center gap-2 text-gray-500">
                <x-filament::loading-indicator class="h-5 w-5" />
                {{ __('filament-web3-auth::wallet.loading') }}
            </div>

            <div id="balance-content" class="hidden">
                <div class="flex items-baseline gap-2">
                    <span id="native-balance" class="text-3xl font-bold text-gray-900 dark:text-white">0.00</span>
                    <span id="native-symbol" class="text-lg text-gray-500 dark:text-gray-400">ETH</span>
                </div>
                <p id="native-balance-usd" class="text-sm text-gray-500 dark:text-gray-400 mt-1"></p>
            </div>

            <div id="balance-error" class="hidden text-danger-600 dark:text-danger-400"></div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                {{ __('filament-web3-auth::wallet.tokens') }}
            </x-slot>

            <div id="tokens-loading" class="flex items-center gap-2 text-gray-500">
                <x-filament::loading-indicator class="h-5 w-5" />
                {{ __('filament-web3-auth::wallet.loading') }}
            </div>

            <div id="tokens-content" class="hidden space-y-3"></div>

            <div id="tokens-empty" class="hidden text-gray-500 dark:text-gray-400">
                {{ __('filament-web3-auth::wallet.no_tokens_found') }}
            </div>

            <div id="tokens-error" class="hidden text-danger-600 dark:text-danger-400"></div>

            <p id="tokens-note" class="hidden text-xs text-gray-400 mt-4">
                {{ __('filament-web3-auth::wallet.tokens_note') }}
            </p>
        </x-filament::section>

        <div class="flex justify-center">
            <a
                id="explorer-link"
                href="https://etherscan.io/address/{{ $this->getEthAddress() }}"
                target="_blank"
                rel="noopener noreferrer"
                class="fi-btn fi-btn-size-md rounded-lg px-4 py-2 text-sm font-semibold shadow-sm ring-1 ring-gray-950/10 dark:ring-white/20 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 inline-flex items-center gap-2"
            >
                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                {{ __('filament-web3-auth::wallet.view_on_explorer') }}
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const address = '{{ $this->getEthAddress() }}';
        let currentNetwork = 'mainnet';

        const networks = {
            mainnet: { rpc: 'https://eth.llamarpc.com', symbol: 'ETH', explorer: 'https://etherscan.io', coingeckoId: 'ethereum' },
            sepolia: { rpc: 'https://rpc.sepolia.org', symbol: 'SEP', explorer: 'https://sepolia.etherscan.io', coingeckoId: null },
            polygon: { rpc: 'https://polygon-rpc.com', symbol: 'MATIC', explorer: 'https://polygonscan.com', coingeckoId: 'matic-network' },
            arbitrum: { rpc: 'https://arb1.arbitrum.io/rpc', symbol: 'ETH', explorer: 'https://arbiscan.io', coingeckoId: 'ethereum' },
            base: { rpc: 'https://mainnet.base.org', symbol: 'ETH', explorer: 'https://basescan.org', coingeckoId: 'ethereum' }
        };

        const popularTokens = {
            mainnet: [
                { address: '0xdAC17F958D2ee523a2206206994597C13D831ec7', symbol: 'USDT', decimals: 6 },
                { address: '0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48', symbol: 'USDC', decimals: 6 },
                { address: '0x6B175474E89094C44Da98b954EesC37d7B35C823', symbol: 'DAI', decimals: 18 },
            ],
            polygon: [
                { address: '0xc2132D05D31c914a87C6611C10748AEb04B58e8F', symbol: 'USDT', decimals: 6 },
                { address: '0x2791Bca1f2de4661ED88A30C99A7a9449Aa84174', symbol: 'USDC', decimals: 6 },
            ],
            arbitrum: [
                { address: '0xFd086bC7CD5C481DCC9C85ebE478A1C0b69FCbb9', symbol: 'USDT', decimals: 6 },
                { address: '0xaf88d065e77c8cC2239327C5EDb3A432268e5831', symbol: 'USDC', decimals: 6 },
            ],
            base: [{ address: '0x833589fCD6eDb6E08f4c7C32D4f71b54bdA02913', symbol: 'USDC', decimals: 6 }],
            sepolia: []
        };

        const ERC20_ABI = [
            'function balanceOf(address owner) view returns (uint256)',
            'function decimals() view returns (uint8)',
            'function symbol() view returns (string)'
        ];

        document.querySelectorAll('.network-btn').forEach(btn => {
            btn.addEventListener('click', function() { selectNetwork(this.dataset.network); });
        });

        function selectNetwork(network) {
            currentNetwork = network;
            document.querySelectorAll('.network-btn').forEach(btn => {
                if (btn.dataset.network === network) {
                    btn.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-200');
                    btn.classList.add('bg-primary-600', 'text-white');
                } else {
                    btn.classList.remove('bg-primary-600', 'text-white');
                    btn.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-200');
                }
            });
            document.getElementById('explorer-link').href = `${networks[network].explorer}/address/${address}`;
            document.getElementById('native-token-name').textContent = networks[network].symbol;
            document.getElementById('native-symbol').textContent = networks[network].symbol;
            loadWalletData();
        }

        async function loadWalletData() {
            await Promise.all([loadNativeBalance(), loadTokens()]);
        }

        async function loadNativeBalance() {
            const loadingEl = document.getElementById('balance-loading');
            const contentEl = document.getElementById('balance-content');
            const errorEl = document.getElementById('balance-error');

            loadingEl.classList.remove('hidden');
            contentEl.classList.add('hidden');
            errorEl.classList.add('hidden');

            try {
                const provider = new ethers.JsonRpcProvider(networks[currentNetwork].rpc);
                const balance = await provider.getBalance(address);
                document.getElementById('native-balance').textContent = parseFloat(ethers.formatEther(balance)).toFixed(6);

                const coingeckoId = networks[currentNetwork].coingeckoId;
                if (coingeckoId) {
                    try {
                        const res = await fetch(`https://api.coingecko.com/api/v3/simple/price?ids=${coingeckoId}&vs_currencies=usd`);
                        const data = await res.json();
                        const price = data[coingeckoId]?.usd || 0;
                        document.getElementById('native-balance-usd').textContent = `≈ $${(parseFloat(ethers.formatEther(balance)) * price).toFixed(2)} USD`;
                    } catch (e) { document.getElementById('native-balance-usd').textContent = ''; }
                } else {
                    document.getElementById('native-balance-usd').textContent = '(Testnet)';
                }
                loadingEl.classList.add('hidden');
                contentEl.classList.remove('hidden');
            } catch (error) {
                loadingEl.classList.add('hidden');
                errorEl.textContent = '{{ __("filament-web3-auth::wallet.error_loading_balance") }}';
                errorEl.classList.remove('hidden');
            }
        }

        async function loadTokens() {
            const loadingEl = document.getElementById('tokens-loading');
            const contentEl = document.getElementById('tokens-content');
            const emptyEl = document.getElementById('tokens-empty');
            const errorEl = document.getElementById('tokens-error');
            const noteEl = document.getElementById('tokens-note');

            loadingEl.classList.remove('hidden');
            contentEl.classList.add('hidden');
            emptyEl.classList.add('hidden');
            errorEl.classList.add('hidden');
            noteEl.classList.add('hidden');

            try {
                const provider = new ethers.JsonRpcProvider(networks[currentNetwork].rpc);
                const tokens = popularTokens[currentNetwork] || [];
                const tokenBalances = [];

                for (const token of tokens) {
                    try {
                        const contract = new ethers.Contract(token.address, ERC20_ABI, provider);
                        const balance = await contract.balanceOf(address);
                        if (balance > 0n) {
                            tokenBalances.push({
                                symbol: token.symbol,
                                balance: parseFloat(ethers.formatUnits(balance, token.decimals)).toFixed(4),
                                address: token.address
                            });
                        }
                    } catch (e) {}
                }

                loadingEl.classList.add('hidden');
                if (tokenBalances.length === 0) {
                    emptyEl.classList.remove('hidden');
                } else {
                    contentEl.innerHTML = tokenBalances.map(t => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold">${t.symbol.substring(0,2)}</div>
                                <span class="font-medium text-gray-900 dark:text-white">${t.symbol}</span>
                            </div>
                            <span class="font-mono text-gray-700 dark:text-gray-300">${t.balance}</span>
                        </div>
                    `).join('');
                    contentEl.classList.remove('hidden');
                    noteEl.classList.remove('hidden');
                }
            } catch (error) {
                loadingEl.classList.add('hidden');
                errorEl.textContent = '{{ __("filament-web3-auth::wallet.error_loading_tokens") }}';
                errorEl.classList.remove('hidden');
            }
        }

        selectNetwork(currentNetwork);
    });
    </script>
</x-filament-panels::page>
