<div class="p-6 max-w-lg mx-auto">
    <div class="card p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center shadow-soft">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Ubah Password</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui kata sandi akun Anda</p>
            </div>
        </div>

        @if (session('warning'))
            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl p-3 mb-5">
                <p class="text-amber-700 dark:text-amber-400 text-sm">⚠ {{ session('warning') }}</p>
            </div>
        @endif

        <form wire:submit="save" class="space-y-5">
            {{-- Current Password --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Lama</label>
                <input type="password" wire:model="current_password" class="input h-11" placeholder="Masukkan password lama" autocomplete="current-password">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Baru</label>
                <input type="password" wire:model="new_password" class="input h-11" placeholder="Minimal 8 karakter, huruf & angka" autocomplete="new-password">
                @error('new_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" wire:model="new_password_confirmation" class="input h-11" placeholder="Ulangi password baru" autocomplete="new-password">
                @error('new_password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Box --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-3">
                <p class="text-blue-700 dark:text-blue-400 text-xs">
                    <strong>Info:</strong> Setelah password berhasil diubah, Anda akan otomatis keluar dari semua perangkat dan harus login ulang.
                </p>
            </div>

            <button type="submit" class="btn-primary w-full h-11" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Password Baru</span>
                <span wire:loading>
                    <svg class="animate-spin h-4 w-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </form>
    </div>
</div>
