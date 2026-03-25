<div class="flex h-screen bg-gray-100 dark:bg-gray-950">

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- LEFT PANEL: Product Grid                                             --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <div class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-5 py-3 flex items-center gap-3 flex-shrink-0">
            {{-- Logo + Store Name --}}
            <div class="flex items-center gap-2 mr-2">
                <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-brand-700 rounded-lg flex items-center justify-center shadow-soft">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="hidden md:block">
                    <div class="text-xs font-bold text-gray-900 dark:text-white leading-tight">{{ auth()->user()->store?->name }}</div>
                    <div class="text-[10px] text-gray-400">{{ auth()->user()->name }}</div>
                </div>
            </div>

            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="search"
                       placeholder="Cari produk, SKU, atau barcode..."
                       class="input pl-9 pr-4 h-10">
            </div>

            {{-- Shift Status --}}
            @if($this->activeShift)
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Shift Aktif</span>
                </div>
            @else
                <a href="{{ route('cashier.shift') }}"
                   class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl text-xs font-medium text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition-colors">
                    ⚠ Buka Shift
                </a>
            @endif

            {{-- Dark Mode Toggle --}}
            <button @click="dark = !dark"
                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                <svg x-show="!dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Category Filter --}}
        <div class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-5 py-2 flex items-center gap-2 overflow-x-auto flex-shrink-0">
            <button wire:click="filterByCategory(null)"
                    class="flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150
                           {{ $selectedCategory === null ? 'bg-brand-600 text-white shadow-soft' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                Semua
            </button>
            @foreach($this->categories as $cat)
                <button wire:click="filterByCategory({{ $cat->id }})"
                        class="flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150
                               {{ $selectedCategory === $cat->id ? 'bg-brand-600 text-white shadow-soft' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto p-4">
            @if($this->products->isEmpty())
                <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                    <svg class="w-16 h-16 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-sm font-medium">Produk tidak ditemukan</p>
                    <p class="text-xs mt-1">Coba kata kunci lain atau ubah filter</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @foreach($this->products as $product)
                        <button wire:click="addToCart({{ $product->id }})"
                                wire:key="product-{{ $product->id }}"
                                @disabled($product->isOutOfStock())
                                class="group relative bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800
                                       hover:border-brand-300 dark:hover:border-brand-700 hover:shadow-soft-lg
                                       transition-all duration-200 text-left active:scale-95 overflow-hidden
                                       {{ $product->isOutOfStock() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">

                            {{-- Product Image --}}
                            <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 relative overflow-hidden">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                                             style="background-color: {{ $product->category?->color ?? '#6366f1' }}">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Stock Badge --}}
                                @if($product->isOutOfStock())
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                        <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">HABIS</span>
                                    </div>
                                @elseif($product->isLowStock())
                                    <div class="absolute top-1.5 right-1.5">
                                        <span class="bg-amber-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">SISA {{ $product->stock }}</span>
                                    </div>
                                @endif

                                {{-- Cart indicator --}}
                                @if(isset($cart[$product->id]))
                                    <div class="absolute top-1.5 left-1.5">
                                        <span class="bg-brand-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                            {{ $cart[$product->id]['quantity'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="p-2.5">
                                <div class="text-xs font-semibold text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1">
                                    {{ $product->name }}
                                </div>
                                <div class="text-sm font-bold text-brand-600 dark:text-brand-400">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                @if($product->category)
                                    <div class="mt-1">
                                        <span class="text-[9px] font-medium px-1.5 py-0.5 rounded-md"
                                              style="background-color: {{ $product->category->color }}22; color: {{ $product->category->color }}">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- RIGHT PANEL: Cart (Sticky)                                           --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    <div class="w-80 xl:w-96 flex flex-col bg-white dark:bg-gray-900 border-l border-gray-100 dark:border-gray-800 flex-shrink-0">

        {{-- Cart Header --}}
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-bold text-gray-900 dark:text-white">Keranjang</span>
                @if($this->cartItemCount > 0)
                    <span class="bg-brand-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $this->cartItemCount }}</span>
                @endif
            </div>
            @if(!empty($cart))
                <button wire:click="clearCart" wire:confirm="Kosongkan keranjang?"
                        class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                    Kosongkan
                </button>
            @endif
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto">
            @if(empty($cart))
                <div class="flex flex-col items-center justify-center h-full text-gray-400 py-16">
                    <svg class="w-14 h-14 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm font-medium">Keranjang kosong</p>
                    <p class="text-xs mt-1 text-center px-8">Klik produk di sebelah kiri untuk menambahkan</p>
                </div>
            @else
                <div class="p-3 space-y-2">
                    @foreach($cart as $productId => $item)
                        <div wire:key="cart-{{ $productId }}"
                             class="flex items-center gap-3 bg-gray-50 dark:bg-gray-800 rounded-xl p-3 group animate-slide-in-right">
                            {{-- Initial Badge --}}
                            <div class="w-9 h-9 rounded-lg bg-brand-100 dark:bg-brand-900/50 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($item['name'], 0, 1)) }}
                            </div>

                            {{-- Name + Price --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                            </div>

                            {{-- Quantity Controls --}}
                            <div class="flex items-center gap-1">
                                <button wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})"
                                        class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-brand-100 dark:hover:bg-brand-900 hover:text-brand-600 dark:hover:text-brand-400 transition-colors text-sm font-bold leading-none">
                                    −
                                </button>
                                <span class="w-7 text-center text-sm font-bold text-gray-900 dark:text-white">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})"
                                        class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-brand-100 dark:hover:bg-brand-900 hover:text-brand-600 dark:hover:text-brand-400 transition-colors text-sm font-bold leading-none">
                                    +
                                </button>
                            </div>

                            {{-- Subtotal + Delete --}}
                            <div class="text-right ml-1">
                                <div class="text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </div>
                                <button wire:click="removeFromCart({{ $productId }})"
                                        class="text-red-400 hover:text-red-600 transition-colors mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Order Summary --}}
        <div class="border-t border-gray-100 dark:border-gray-800 p-4 space-y-3">

            {{-- Discount --}}
            <div class="flex items-center gap-3">
                <label class="text-xs text-gray-500 dark:text-gray-400 w-20 flex-shrink-0">Diskon (%)</label>
                <input wire:model.live="discountPercent" type="number" min="0" max="100"
                       class="input text-right h-8 text-sm" placeholder="0">
            </div>

            {{-- Tax --}}
            <div class="flex items-center gap-3">
                <label class="text-xs text-gray-500 dark:text-gray-400 w-20 flex-shrink-0">PPN (%)</label>
                <input wire:model.live="taxPercent" type="number" min="0" max="100"
                       class="input text-right h-8 text-sm" placeholder="0">
            </div>

            {{-- Totals --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-3 space-y-1.5 text-xs">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($discountPercent > 0)
                    <div class="flex justify-between text-emerald-600">
                        <span>Diskon ({{ $discountPercent }}%)</span>
                        <span>− Rp {{ number_format($this->discountAmount, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($taxPercent > 0)
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>PPN ({{ $taxPercent }}%)</span>
                        <span>+ Rp {{ number_format($this->taxAmount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 dark:text-white text-sm pt-1.5 border-t border-gray-200 dark:border-gray-700">
                    <span>Total</span>
                    <span class="text-brand-600 dark:text-brand-400">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Checkout Button --}}
            <button wire:click="openCheckout"
                    {{ empty($cart) ? 'disabled' : '' }}
                    class="w-full btn-primary h-12 text-base {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════ CHECKOUT MODAL ════════════════════════════════════ --}}
@if($showCheckoutModal)
<div class="modal-overlay" x-data x-show="true">
    <div class="modal-box max-w-md" @click.stop>
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Konfirmasi Pembayaran</h2>
            <button wire:click="$set('showCheckoutModal', false)" class="btn-ghost p-1.5 rounded-lg">✕</button>
        </div>

        <div class="p-5 space-y-4">
            {{-- Total Display --}}
            <div class="text-center bg-brand-50 dark:bg-brand-900/30 rounded-xl p-4">
                <div class="text-xs text-brand-600 dark:text-brand-400 font-medium mb-1">Total Pembayaran</div>
                <div class="text-3xl font-black text-brand-700 dark:text-brand-300">
                    Rp {{ number_format($this->total, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ count($cart) }} jenis produk · {{ $this->cartItemCount }} item</div>
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 block">Metode Pembayaran</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['cash' => ['💵', 'Tunai'], 'qris' => ['📱', 'QRIS'], 'transfer' => ['🏦', 'Transfer'], 'card' => ['💳', 'Kartu']] as $method => [$icon, $label])
                        <button wire:click="$set('paymentMethod', '{{ $method }}')"
                                class="flex flex-col items-center gap-1 py-2.5 rounded-xl border-2 text-xs font-semibold transition-all duration-150
                                       {{ $paymentMethod === $method
                                            ? 'border-brand-500 bg-brand-50 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300'
                                            : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:border-gray-300' }}">
                            <span class="text-lg">{{ $icon }}</span>
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Cash Input --}}
            @if($paymentMethod === 'cash')
                <div>
                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 block">Uang Diterima</label>
                    <input wire:model.live="amountPaid" type="number" min="0" step="1000"
                           class="input text-lg font-bold text-right h-12"
                           placeholder="{{ number_format($this->total, 0) }}">
                    @error('amountPaid') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    {{-- Quick Amount Buttons --}}
                    <div class="grid grid-cols-4 gap-1.5 mt-2">
                        @foreach([5000, 10000, 20000, 50000, 100000] as $amount)
                            <button wire:click="$set('amountPaid', {{ ceil($this->total / $amount) * $amount }})"
                                    class="py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-brand-50 dark:hover:bg-brand-900/30 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                {{ number_format($amount, 0, ',', '.') }}
                            </button>
                        @endforeach
                        <button wire:click="$set('amountPaid', $this->total)"
                                class="py-1.5 text-xs font-medium rounded-lg bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 hover:bg-brand-100 transition-colors">
                            Pas
                        </button>
                    </div>

                    {{-- Change --}}
                    <div class="mt-3 flex items-center justify-between bg-emerald-50 dark:bg-emerald-900/30 rounded-xl px-4 py-3">
                        <span class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">Kembalian</span>
                        <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($this->changeAmount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endif

            {{-- QRIS / Transfer Reference --}}
            @if(in_array($paymentMethod, ['qris', 'transfer', 'card']))
                <div>
                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 block">
                        {{ $paymentMethod === 'qris' ? 'ID Transaksi QRIS' : ($paymentMethod === 'transfer' ? 'Nomor Referensi Transfer' : 'No. Approval Kartu') }}
                        <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input wire:model="paymentReference" type="text"
                           class="input"
                           placeholder="{{ $paymentMethod === 'qris' ? 'Cth: TXN-202412...' : 'Masukkan referensi...' }}">
                </div>
            @endif

            {{-- Notes --}}
            <div>
                <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 block">Catatan (Opsional)</label>
                <input wire:model="notes" type="text" class="input" placeholder="Catatan untuk transaksi ini...">
            </div>
        </div>

        <div class="p-5 border-t border-gray-100 dark:border-gray-800 flex gap-3">
            <button wire:click="$set('showCheckoutModal', false)" class="btn-secondary flex-1">Batal</button>
            <button wire:click="processCheckout"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75"
                    class="btn-success flex-1">
                <span wire:loading.remove>✓ Proses Pembayaran</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════ SUCCESS MODAL + PRINT ════════════════════════════ --}}
@if($showSuccessModal)
<div class="modal-overlay" x-data x-show="true">
    <div class="modal-box max-w-sm text-center" @click.stop>
        <div class="p-8">
            <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Transaksi Berhasil!</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Pembayaran telah diproses dan stok telah diperbarui.</p>

            <div class="flex flex-col gap-2">
                {{-- Print Button (Web Serial API) --}}
                <button
                    x-data
                    @click="printReceipt()"
                    class="btn-primary w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Struk
                </button>
                <button wire:click="closeSuccessModal" class="btn-secondary w-full">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Web Serial API Thermal Printer Script --}}
<script>
/**
 * Web Serial API — Thermal Printer Receipt Printer
 * Compatible with ESC/POS thermal printers (Epson, SUNMI, Xprinter, etc.)
 */
async function printReceipt() {
    if (!('serial' in navigator)) {
        alert('Browser Anda tidak mendukung Web Serial API.\nGunakan Chrome atau Edge versi terbaru.');
        return;
    }

    try {
        // Connect to thermal printer via USB/Serial
        const port = await navigator.serial.requestPort();
        await port.open({ baudRate: 9600 });

        const writer = port.writable.getWriter();
        const encoder = new TextEncoder();

        // ESC/POS Commands
        const ESC = 0x1B;
        const GS  = 0x1D;

        // Initialize printer
        const init        = new Uint8Array([ESC, 0x40]);
        // Center alignment
        const center      = new Uint8Array([ESC, 0x61, 0x01]);
        // Left alignment
        const left        = new Uint8Array([ESC, 0x61, 0x00]);
        // Bold on/off
        const boldOn      = new Uint8Array([ESC, 0x45, 0x01]);
        const boldOff     = new Uint8Array([ESC, 0x45, 0x00]);
        // Double size on/off
        const dblSizeOn   = new Uint8Array([GS,  0x21, 0x11]);
        const dblSizeOff  = new Uint8Array([GS,  0x21, 0x00]);
        // Separator
        const separator   = encoder.encode('================================\n');
        // Cut paper
        const cut         = new Uint8Array([GS,  0x56, 0x42, 0x03]);

        // Data from Livewire (injected server-side)
        @php
            $receiptStoreName   = auth()->user()->store?->name ?? 'POS SaaS';
            $receiptStoreAddr   = auth()->user()->store?->address ?? '';
            $receiptCashier     = auth()->user()->name;
            $receiptInvoice     = $lastTransactionId ? 'INV-'.str_pad($lastTransactionId, 6, '0', STR_PAD_LEFT) : 'N/A';
            $receiptTotal       = number_format($this->total, 0, ',', '.');
            $receiptPayMethod   = ucfirst($paymentMethod);
            $receiptItems       = collect($cart)->values()->toArray();
            $receiptNow         = now()->format('d/m/Y H:i:s');
        @endphp
        const storeName   = @json($receiptStoreName);
        const storeAddr   = @json($receiptStoreAddr);
        const cashierName = @json($receiptCashier);
        const invoiceNum  = @json($receiptInvoice);
        const total       = @json($receiptTotal);
        const payMethod   = @json($receiptPayMethod);
        const cartItems   = @json($receiptItems);
        const now         = @json($receiptNow);

        // Build receipt
        await writer.write(init);
        await writer.write(center);
        await writer.write(dblSizeOn);
        await writer.write(boldOn);
        await writer.write(encoder.encode(storeName + '\n'));
        await writer.write(dblSizeOff);
        await writer.write(boldOff);
        if (storeAddr) {
            await writer.write(encoder.encode(storeAddr + '\n'));
        }
        await writer.write(encoder.encode(now + '\n'));
        await writer.write(separator);

        await writer.write(left);
        await writer.write(encoder.encode('Kasir  : ' + cashierName + '\n'));
        await writer.write(encoder.encode('Invoice: ' + invoiceNum + '\n'));
        await writer.write(separator);

        // Items
        cartItems.forEach(item => {
            const name    = item.name.substring(0, 20).padEnd(20, ' ');
            const qty     = String(item.quantity).padStart(3, ' ');
            const price   = 'Rp ' + new Intl.NumberFormat('id-ID').format(item.price);
            const sub     = new Intl.NumberFormat('id-ID').format(item.price * item.quantity);
            writer.write(encoder.encode(name + '\n'));
            writer.write(encoder.encode('  ' + qty + ' x ' + price + '   Rp ' + sub + '\n'));
        });

        await writer.write(separator);
        await writer.write(boldOn);
        await writer.write(encoder.encode('TOTAL  : Rp ' + total + '\n'));
        await writer.write(boldOff);
        await writer.write(encoder.encode('Bayar  : ' + payMethod + '\n'));
        await writer.write(separator);
        await writer.write(center);
        await writer.write(encoder.encode('Terima kasih atas kunjungan Anda!\n'));
        await writer.write(encoder.encode('Barang yang sudah dibeli\ntidak dapat dikembalikan.\n\n\n'));

        await writer.write(cut);
        writer.releaseLock();
        await port.close();

    } catch (err) {
        if (err.name !== 'NotFoundError') {
            console.error('Printer error:', err);
            alert('Gagal mencetak: ' + err.message);
        }
    }
}
</script>
@endif
