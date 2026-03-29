<div class="p-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                Permintaan Reset Password
                @if($this->pendingCount > 0)
                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                        {{ $this->pendingCount }}
                    </span>
                @endif
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola permintaan reset password dari pengguna</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" class="input h-10 w-full" placeholder="Cari nama atau email...">
            </div>
            <div class="flex gap-2">
                <select wire:model.live="statusFilter" class="input h-10">
                    <option value="">Semua Status</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="approved">✓ Approved</option>
                    <option value="rejected">✕ Rejected</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengguna</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tgl Request</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->requests as $req)
                        <tr class="border-b border-gray-50 dark:border-gray-800/50 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-brand-400 to-brand-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-soft">
                                        {{ strtoupper(substr($req->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $req->user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $req->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 capitalize">
                                    {{ str_replace('_', ' ', $req->user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">
                                {{ $req->requested_at->format('d M Y') }}
                                <div class="text-xs text-gray-400 dark:text-gray-500">{{ $req->requested_at->format('H:i') }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($req->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Pending
                                    </span>
                                @elseif($req->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Approved
                                    </span>
                                @elseif($req->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($req->status === 'pending')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button wire:click="openApprove({{ $req->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400 dark:hover:bg-emerald-900/60 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                        <button wire:click="openReject({{ $req->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-400 dark:hover:bg-red-900/60 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada permintaan reset password</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($this->requests->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800">
                {{ $this->requests->links() }}
            </div>
        @endif
    </div>

    {{-- Approve Modal --}}
    @if($showApproveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="$set('showApproveModal', false)">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Approve Reset Password</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Tentukan password baru untuk pengguna ini.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Baru</label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="newPassword" class="input h-10 flex-1 font-mono" placeholder="Masukkan atau generate password">
                            <button wire:click="generatePassword" type="button"
                                    class="px-3 h-10 rounded-xl text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                🔄 Generate
                            </button>
                        </div>
                        @error('newPassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3">
                        <p class="text-amber-700 dark:text-amber-400 text-xs">
                            <strong>Perhatian:</strong> Password ini akan langsung diterapkan. User akan diminta mengganti password saat login berikutnya. Pastikan Anda menginformasikan password sementara kepada user.
                        </p>
                    </div>
                </div>

                <div class="flex gap-2 mt-6">
                    <button wire:click="$set('showApproveModal', false)" type="button"
                            class="flex-1 h-10 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button wire:click="approve" type="button"
                            class="flex-1 h-10 rounded-xl text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-soft transition-colors">
                        ✓ Approve & Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="$set('showRejectModal', false)">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Tolak Permintaan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Apakah Anda yakin ingin menolak permintaan reset password ini?</p>

                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-3 mb-5">
                    <p class="text-red-700 dark:text-red-400 text-xs">
                        Permintaan akan ditandai sebagai ditolak. Pengguna dapat mengajukan permintaan baru.
                    </p>
                </div>

                <div class="flex gap-2">
                    <button wire:click="$set('showRejectModal', false)" type="button"
                            class="flex-1 h-10 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button wire:click="reject" type="button"
                            class="flex-1 h-10 rounded-xl text-sm font-semibold bg-red-600 text-white hover:bg-red-700 shadow-soft transition-colors">
                        ✕ Tolak Permintaan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
