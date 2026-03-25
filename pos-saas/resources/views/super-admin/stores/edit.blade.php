<x-app-layout title="Edit Toko: {{ $store->name }}">
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Informasi Toko</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui detail profil toko {{ $store->name }}.</p>
            </div>
            <a href="{{ route('super-admin.stores.index') }}" class="btn-ghost">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('super-admin.stores.update', $store) }}" class="card p-6 space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Toko *</label>
                    <input type="text" name="name" value="{{ old('name', $store->name) }}" required class="input">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat Lengkap</label>
                    <input type="text" name="address" value="{{ old('address', $store->address) }}" class="input">
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $store->phone) }}" class="input">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Mata Uang</label>
                    <input type="text" name="currency" value="{{ old('currency', $store->currency) }}" class="input">
                    @error('currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="btn-primary w-full sm:w-auto px-8 h-12">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
