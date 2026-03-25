<x-app-layout title="Dashboard Admin">
    <div class="p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Ringkasan Hari Ini</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- Sales --}}
            <div class="card p-5 border-l-4 border-l-emerald-500">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Penjualan Hari Ini</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
            </div>
            {{-- Transactions --}}
            <div class="card p-5 border-l-4 border-l-brand-500">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Transaksi</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($todayTransactions) }}</div>
            </div>
            {{-- Products --}}
            <div class="card p-5 border-l-4 border-l-blue-500">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Produk Aktif</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</div>
            </div>
            {{-- Low Stock --}}
            <div class="card p-5 border-l-4 {{ $lowStockProducts > 0 ? 'border-l-red-500 bg-red-50/50 dark:bg-red-900/10' : 'border-l-gray-300' }}">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Stok Menipis</div>
                <div class="text-2xl font-black {{ $lowStockProducts > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ number_format($lowStockProducts) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Transactions --}}
            <div class="lg:col-span-2 card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white">Transaksi Terakhir Hari Ini</h3>
                    <a href="#" class="text-sm text-brand-600 dark:text-brand-400 font-semibold hover:underline">Lihat Semua</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($recentTransactions as $trx)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300">
                                    {{ $trx->payment_method === 'cash' ? 'CASH' : strtoupper($trx->payment_method) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">INV-{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $trx->created_at->format('H:i') }} · Kasir: {{ $trx->user?->name ?? 'Unknown' }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-900 dark:text-white text-sm">Rp {{ number_format($trx->total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 text-sm">
                            Belum ada transaksi hari ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Shift Status --}}
            <div class="card h-fit">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Status Kasir (Shift)</h3>
                </div>
                <div class="p-5">
                    @if($activeShift)
                        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 bg-white dark:bg-emerald-800 rounded-full flex items-center justify-center mx-auto mb-2 shadow-soft">
                                <span class="w-4 h-4 bg-emerald-500 rounded-full animate-pulse"></span>
                            </div>
                            <h4 class="font-bold text-emerald-800 dark:text-emerald-300">{{ $activeShift->user?->name }}</h4>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Sedang bertugas (Sejak {{ $activeShift->opened_at->format('H:i') }})</p>
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-2 shadow-soft opacity-50">
                                🔒
                            </div>
                            <h4 class="font-bold text-gray-700 dark:text-gray-300">Shift Tutup</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada kasir yang sedang bertugas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
