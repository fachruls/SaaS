<x-app-layout title="Daftarkan Toko Baru">
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftarkan Toko Baru (Tenant)</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat workspace toko baru beserta otomatis akun admin pertamanya.</p>
            </div>
            <a href="{{ route('super-admin.stores.index') }}" class="btn-ghost">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('super-admin.stores.store') }}" class="card p-6 space-y-8">
            @csrf

            {{-- Store Data --}}
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">1. Informasi Toko Pengecer</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Toko *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="input" placeholder="Toko Sejahtera">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat Lengkap</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="input" placeholder="Jl. Sudirman No. 123">
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="input" placeholder="021-12345678">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Mata Uang</label>
                        <input type="text" name="currency" value="{{ old('currency', 'IDR') }}" class="input" placeholder="IDR">
                        @error('currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- First Admin User --}}
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">2. Akun Admin Toko Pertama</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap Admin *</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required class="input" placeholder="Budi Santoso">
                        @error('admin_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat Email *</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required class="input" placeholder="admin@tokosejahtera.com">
                        @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kata Sandi (Min. 8 Karakter) *</label>
                        <input type="password" name="admin_password" required class="input" placeholder="••••••••">
                        @error('admin_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="btn-primary w-full sm:w-auto px-8 h-12">
                    Buat Toko & Akun Admin
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
