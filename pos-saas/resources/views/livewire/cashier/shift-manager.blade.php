<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Shift Kasir</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Buka dan tutup shift dengan pencatatan saldo</p>
    </div>

    {{-- Active Shift Card --}}
    @if($this->activeShift)
        <div class="card mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center">
                            <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white">Shift Aktif</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Dibuka {{ $this->activeShift->opened_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span class="badge-green">🟢 Sedang Berjalan</span>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center bg-gray-50 dark:bg-gray-800 rounded-xl p-3">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Saldo Awal</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($this->activeShift->opening_balance, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-center bg-emerald-50 dark:bg-emerald-900/30 rounded-xl p-3">
                        <div class="text-xs text-emerald-600 dark:text-emerald-400">Total Penjualan</div>
                        <div class="text-lg font-bold text-emerald-700 dark:text-emerald-300">
                            Rp {{ number_format($this->activeShift->total_sales, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-center bg-brand-50 dark:bg-brand-900/30 rounded-xl p-3">
                        <div class="text-xs text-brand-600 dark:text-brand-400">Total Transaksi</div>
                        <div class="text-lg font-bold text-brand-700 dark:text-brand-300">
                            {{ $this->activeShift->total_transactions }} Transaksi
                        </div>
                    </div>
                </div>

                <button wire:click="openCloseModal" class="btn-danger w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tutup Shift
                </button>
            </div>
        </div>
    @else
        {{-- Open Shift Form --}}
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="font-bold text-gray-900 dark:text-white">Buka Shift Baru</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Saldo Awal (Kas)</label>
                    <input wire:model="openingBalance" type="number" min="0" class="input h-11" placeholder="0">
                    @error('openingBalance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Catatan (Opsional)</label>
                    <input wire:model="openingNotes" type="text" class="input" placeholder="Catatan pembukaan shift...">
                </div>
                <button wire:click="openShift" class="btn-primary w-full h-11">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buka Shift
                </button>
            </div>
        </div>
    @endif

    {{-- Shift History --}}
    @if($this->shiftHistory->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Shift</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($this->shiftHistory as $shift)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $shift->opened_at->format('d M Y, H:i') }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Ditutup {{ $shift->closed_at?->format('H:i') }} · {{ $shift->total_transactions }} transaksi
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($shift->total_sales, 0, ',', '.') }}
                            </div>
                            <span class="badge-green text-[10px]">Selesai</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Close Shift Modal --}}
    @if($showCloseModal)
        <div class="modal-overlay" x-data x-show="true">
            <div class="modal-box" @click.stop>
                <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 dark:text-white">Tutup Shift</h3>
                    <button wire:click="$set('showCloseModal', false)" class="btn-ghost p-1.5 rounded-lg">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Saldo Awal:</span>
                            <span class="font-semibold">Rp {{ number_format($this->activeShift?->opening_balance ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Penjualan:</span>
                            <span class="font-semibold text-emerald-600">Rp {{ number_format($this->activeShift?->total_sales ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Saldo Akhir (Kas)</label>
                        <input wire:model="closingBalance" type="number" min="0" class="input h-11">
                        @error('closingBalance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Catatan Penutupan</label>
                        <input wire:model="closingNotes" type="text" class="input" placeholder="Catatan...">
                    </div>
                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 flex gap-3">
                    <button wire:click="$set('showCloseModal', false)" class="btn-secondary flex-1">Batal</button>
                    <button wire:click="closeShift" class="btn-danger flex-1">Tutup Shift</button>
                </div>
            </div>
        </div>
    @endif
</div>
