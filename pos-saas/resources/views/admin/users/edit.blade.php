<x-app-layout title="Edit Pengguna: {{ $user->name }}">
    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Akses Pengguna</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui profil dan wewenang pengguna {{ $user->name }}.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost">Kembali</a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="card p-6 space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-500 dark:text-gray-500 mb-1">Email / ID Login</label>
                    <input type="email" value="{{ $user->email }}" disabled class="input bg-gray-100 dark:bg-gray-800 opacity-70 cursor-not-allowed">
                    <p class="text-[10px] text-gray-400 mt-1">Email tidak dapat diubah setelah dibuat.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Peran Akses *</label>
                    <select name="role" required class="input">
                        <option value="cashier" {{ old('role', $user->role) === 'cashier' ? 'selected' : '' }}>Kasir (POS)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin Toko</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded">
                    <label for="is_active" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Akun Aktif (Dapat Login)</label>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="btn-primary w-full px-8 h-12">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>
