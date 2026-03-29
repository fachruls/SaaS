<!DOCTYPE html>
<html lang="id"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
    x-init="$watch('dark', v => localStorage.setItem('theme', v ? 'dark' : 'light'));
            document.documentElement.classList.toggle('dark', dark);"
    :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Reset Password — Sellvix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-600 via-brand-700 to-indigo-900 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 flex items-center justify-center p-4 transition-colors duration-300">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-lg rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-soft-lg border border-white/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white">Status Permintaan</h1>
            <p class="text-white/60 text-sm mt-1">Cek status reset password Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-soft-lg p-8 border border-white/20">

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-3 mb-5">
                    <p class="text-red-700 dark:text-red-400 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('reset-status') }}" class="space-y-4 mb-6">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus
                           class="input h-11"
                           placeholder="email@contoh.com">
                </div>

                <button type="submit" class="btn-primary w-full h-11">
                    Cek Status
                </button>
            </form>

            {{-- Results --}}
            @if(isset($searched))
                @if($request)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Status</span>
                            @if($request->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400">
                                    ⏳ Menunggu Persetujuan
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                                    ✓ Disetujui
                                </span>
                            @elseif($request->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                    ✕ Ditolak
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tanggal Request</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $request->requested_at->format('d M Y, H:i') }}</span>
                        </div>

                        @if($request->approved_at)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tanggal Proses</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $request->approved_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif

                        @if($request->status === 'approved')
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-3 mt-2">
                                <p class="text-emerald-700 dark:text-emerald-400 text-xs">
                                    Password Anda telah direset. Silakan login dan Anda akan diminta membuat password baru.
                                </p>
                            </div>
                        @elseif($request->status === 'pending')
                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 mt-2">
                                <p class="text-amber-700 dark:text-amber-400 text-xs">
                                    Permintaan Anda sedang menunggu persetujuan Super Admin. Mohon bersabar.
                                </p>
                            </div>
                        @elseif($request->status === 'rejected')
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 mt-2">
                                <p class="text-red-700 dark:text-red-400 text-xs">
                                    Permintaan Anda ditolak. Silakan hubungi administrator.
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada permintaan reset password ditemukan untuk email ini.</p>
                    </div>
                @endif
            @endif

            <div class="mt-6 flex flex-col gap-2 text-center">
                <a href="{{ route('forgot-password') }}" class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-medium">
                    Ajukan Permintaan Baru →
                </a>
                <a href="{{ route('login') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    ← Kembali ke Login
                </a>
            </div>
        </div>

        {{-- Dark Mode Toggle --}}
        <div class="text-center mt-4">
            <button @click="dark = !dark" class="text-white/60 hover:text-white text-xs transition-colors">
                <span x-text="dark ? '☀ Mode Terang' : '🌙 Mode Gelap'"></span>
            </button>
        </div>
    </div>
</body>
</html>
