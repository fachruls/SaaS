<!DOCTYPE html>
<html lang="id"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
    x-init="$watch('dark', v => localStorage.setItem('theme', v ? 'dark' : 'light'));
            document.documentElement.classList.toggle('dark', dark);"
    :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Sellvix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-600 via-brand-700 to-indigo-900 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 flex items-center justify-center p-4 transition-colors duration-300">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-lg rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-soft-lg border border-white/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white">Lupa Password</h1>
            <p class="text-white/60 text-sm mt-1">Kirim permintaan reset password</p>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-soft-lg p-8 border border-white/20">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Reset Password 🔑</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Masukkan email Anda, kami akan mengirim permintaan ke Super Admin.</p>

            @if(session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3 mb-5">
                    <p class="text-emerald-700 dark:text-emerald-400 text-sm">✓ {{ session('status') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-3 mb-5">
                    <p class="text-red-700 dark:text-red-400 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input h-11"
                           placeholder="email@contoh.com">
                </div>

                <button type="submit" class="btn-primary w-full h-11">
                    Kirim Permintaan
                </button>
            </form>

            <div class="mt-6 flex flex-col gap-2 text-center">
                <a href="{{ route('reset-status') }}" class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-medium">
                    Cek Status Permintaan →
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
