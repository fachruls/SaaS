<x-app-layout title="Kelola Toko (Tenant)">
    <div class="p-6">
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 animate-fade-in shadow-soft">
                <span class="font-bold">✓</span> {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Toko</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar semua tenant yang menggunakan aplikasi kasir SaaS ini.</p>
            </div>
            <a href="{{ route('super-admin.stores.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Daftarkan Toko Baru
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Info Toko</th>
                            <th class="text-center px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Pengguna</th>
                            <th class="text-center px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Produk</th>
                            <th class="text-center px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Transaksi</th>
                            <th class="text-center px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($stores as $store)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $store->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">{{ $store->address ?? 'Tidak ada alamat' }}</div>
                                    <div class="text-[10px] text-gray-400 mt-1 uppercase tracker-wider">{{ $store->slug }}</div>
                                </td>
                                <td class="px-5 py-4 text-center font-medium">{{ $store->users_count }}</td>
                                <td class="px-5 py-4 text-center font-medium">{{ $store->products_count }}</td>
                                <td class="px-5 py-4 text-center font-medium">{{ $store->transactions_count }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="{{ $store->is_active ? 'badge-green' : 'badge-red' }}">
                                        {{ $store->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('super-admin.stores.toggle', $store) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold px-2 py-1 {{ $store->is_active ? 'text-amber-500 hover:text-amber-700' : 'text-emerald-500 hover:text-emerald-700' }}">
                                                {{ $store->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('super-admin.stores.edit', $store) }}" class="text-blue-500 hover:text-blue-700 font-semibold text-xs px-2 py-1">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('super-admin.stores.destroy', $store) }}" onsubmit="return confirm('Kunci/Hapus seluruh data toko ini? Tindakan ini tidak bisa dibatalkan!');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-xs px-2 py-1">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                    Belum ada toko yang didaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($stores->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $stores->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
