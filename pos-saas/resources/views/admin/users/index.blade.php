<x-app-layout title="Manajemen Pengguna">
    <div class="p-6">
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-soft">
                <span class="font-bold">✓</span> {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <span class="font-bold">✕</span> {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengguna Aplikasi</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola akses kasir dan admin di toko Anda.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                + Tambah Pengguna
            </a>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Nama Lengkap</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Email / ID</th>
                            <th class="text-center px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Status & Peran</th>
                            <th class="text-right px-5 py-3 font-semibold text-gray-600 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-5 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                    @if(auth()->id() === $user->id)
                                        <span class="ml-2 badge-brand text-[10px]">Anda</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="{{ $user->role === 'admin' ? 'badge-purple' : 'badge-blue' }}">
                                            {{ strtoupper($user->role) }}
                                        </span>
                                        @if(!$user->is_active)
                                            <span class="text-[10px] text-red-500 font-bold">Dinonaktifkan</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary px-3 py-1.5 text-xs">Edit</a>
                                        @if(auth()->id() !== $user->id)
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus akses pengguna ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger px-3 py-1.5 text-xs">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-gray-400">Belum ada pengguna lain.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
