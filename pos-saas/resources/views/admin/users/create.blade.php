<x-app-layout title="Tambah Pengguna">
    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Pengguna</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat akun untuk kasir atau admin toko tambahan.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost">Kembali</a>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="card p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email / ID Login *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kata Sandi (Min 8. Karakter) *</label>
                    <input type="password" name="password" required class="input">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Peran Akses *</label>
                    <select name="role" required class="input">
                        <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Kasir (POS)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Toko</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="btn-primary w-full px-8 h-12">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</x-app-layout>
