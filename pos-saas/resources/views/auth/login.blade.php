<!DOCTYPE html>
<html lang="id"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
    x-init="$watch('dark', v => localStorage.setItem('theme', v ? 'dark' : 'light'));
            document.documentElement.classList.toggle('dark', dark);"
    :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Sellvix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-600 via-brand-700 to-indigo-900 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 flex items-center justify-center p-4 transition-colors duration-300">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-lg rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-soft-lg border border-white/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white">Sellvix</h1>
            <p class="text-white/60 text-sm mt-1">Sistem Kasir Multi-Toko</p>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-soft-lg p-8 border border-white/20">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Selamat Datang 👋</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Masuk ke akun Anda untuk melanjutkan</p>

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-3 mb-5">
                    <p class="text-red-700 dark:text-red-400 text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            @if(session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3 mb-5">
                    <p class="text-emerald-700 dark:text-emerald-400 text-sm">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input h-11"
                           placeholder="admin@toko.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kata Sandi</label>
                        <a href="{{ route('forgot-password') }}" class="text-xs text-brand-600 dark:text-brand-400 hover:underline font-medium">Lupa Password?</a>
                    </div>
                    <input type="password" name="password" required
                           class="input h-11"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 text-brand-600 rounded border-gray-300 dark:border-gray-600">
                    <label for="remember" class="text-sm text-gray-600 dark:text-gray-400">Ingat saya</label>
                </div>

                <button type="submit" class="btn-primary w-full h-11 mt-2">
                    Masuk ke Sistem
                </button>
            </form>
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
