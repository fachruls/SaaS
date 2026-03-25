<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola produk yang tersedia di toko Anda</p>
        </div>
        <button wire:click="openCreateForm" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </button>
    </div>

    {{-- Filters --}}
    <div class="card mb-5">
        <div class="p-4 flex flex-col sm:flex-row gap-3">
            <input wire:model.live.debounce.300ms="search" type="search"
                   class="input flex-1" placeholder="Cari nama produk, SKU...">
            <select wire:model.live="categoryFilter" class="input w-full sm:w-48">
                <option value="">Semua Kategori</option>
                @foreach($this->categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Produk</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Kategori</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Harga</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Stok</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($this->products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" wire:key="row-{{ $product->id }}">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-xs text-gray-400">{{ $product->sku }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="{{ $product->isOutOfStock() ? 'badge-red' : ($product->isLowStock() ? 'badge-yellow' : 'badge-green') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="{{ $product->is_active ? 'badge-green' : 'badge-red' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditForm({{ $product->id }})" class="btn-secondary px-3 py-1.5 text-xs">Edit</button>
                                    <button wire:click="delete({{ $product->id }})" wire:confirm="Hapus produk ini?" class="btn-danger px-3 py-1.5 text-xs">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Belum ada produk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800">{{ $this->products->links() }}</div>
    </div>

    {{-- Product Form Modal --}}
    @if($showForm)
    <div class="modal-overlay" x-data x-show="true">
        <div class="modal-box max-w-lg overflow-y-auto max-h-screen" @click.stop>
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 dark:text-white">{{ $editingId ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                <button wire:click="$set('showForm', false)" class="btn-ghost p-1.5 rounded-lg">✕</button>
            </div>
            <div class="p-5 space-y-4">
                <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Produk *</label>
                    <input wire:model="name" type="text" class="input" placeholder="Masukkan nama produk">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">SKU</label>
                        <input wire:model="sku" type="text" class="input" placeholder="SKU-001"></div>
                    <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                        <select wire:model="categoryId" class="input">
                            <option value="">Pilih Kategori</option>
                            @foreach($this->categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                        </select></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga Jual *</label>
                        <input wire:model="price" type="number" min="0" class="input">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                    <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga Modal</label>
                        <input wire:model="costPrice" type="number" min="0" class="input"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Stok *</label>
                        <input wire:model="stock" type="number" min="0" class="input">
                        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                    <div><label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Alert Stok Minimum</label>
                        <input wire:model="lowStockAlert" type="number" min="0" class="input"></div>
                </div>
                <div class="flex items-center gap-2">
                    <input wire:model="isActive" type="checkbox" id="isActive" class="w-4 h-4 text-brand-600 rounded">
                    <label for="isActive" class="text-sm text-gray-700 dark:text-gray-300">Produk Aktif</label>
                </div>
            </div>
            <div class="p-5 border-t border-gray-100 dark:border-gray-800 flex gap-3">
                <button wire:click="$set('showForm', false)" class="btn-secondary flex-1">Batal</button>
                <button wire:click="save" class="btn-primary flex-1">
                    {{ $editingId ? 'Simpan Perubahan' : 'Tambah Produk' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
