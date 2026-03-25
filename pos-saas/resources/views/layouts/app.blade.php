<!DOCTYPE html>
<html lang="id"
    x-data="{ dark: localStorage.getItem('theme') === 'dark', sidebarOpen: false }"
    x-init="$watch('dark', v => {
        localStorage.setItem('theme', v ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', v);
    }); document.documentElement.classList.toggle('dark', dark);"
    :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} — POS SaaS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white antialiased transition-colors duration-300">

{{-- Toast Notification --}}
<div
    x-data="{
        notifications: [],
        add(event) {
            let n = { id: Date.now(), type: event.detail.type, message: event.detail.message, show: true };
            this.notifications.push(n);
            setTimeout(() => this.remove(n.id), 4000);
        },
        remove(id) { this.notifications = this.notifications.filter(n => n.id !== id); }
    }"
    @notify.window="add($event)"
    class="fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full">
    <template x-for="n in notifications" :key="n.id">
        <div x-show="n.show" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             :class="{
                'bg-emerald-50 border-emerald-300 text-emerald-800 dark:bg-emerald-900/80 dark:border-emerald-700 dark:text-emerald-200': n.type === 'success',
                'bg-red-50 border-red-300 text-red-800 dark:bg-red-900/80 dark:border-red-700 dark:text-red-200': n.type === 'error',
                'bg-amber-50 border-amber-300 text-amber-800 dark:bg-amber-900/80 dark:border-amber-700 dark:text-amber-200': n.type === 'warning',
                'bg-blue-50 border-blue-300 text-blue-800 dark:bg-blue-900/80 dark:border-blue-700 dark:text-blue-200': n.type === 'info',
             }"
             class="flex items-start gap-3 px-4 py-3 rounded-xl border shadow-soft-lg text-sm font-medium animate-slide-in-right">
            <span x-text="n.type === 'success' ? '✓' : n.type === 'error' ? '✕' : '!'"></span>
            <span x-text="n.message" class="flex-1"></span>
            <button @click="remove(n.id)" class="opacity-50 hover:opacity-100 transition-opacity">✕</button>
        </div>
    </template>
</div>

<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    <aside class="hidden lg:flex flex-col w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 flex-shrink-0">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center shadow-soft">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">POS SaaS</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[120px]">
                    {{ auth()->user()?->store?->name ?? 'Super Admin' }}
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @if(auth()->user()?->isSuperAdmin())
                <x-sidebar-link href="{{ route('super-admin.dashboard') }}" icon="home">Dashboard</x-sidebar-link>
                <x-sidebar-link href="{{ route('super-admin.stores.index') }}" icon="store">Kelola Toko</x-sidebar-link>
            @elseif(auth()->user()?->isAdmin())
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="home">Dashboard</x-sidebar-link>
                <x-sidebar-link href="{{ route('cashier.pos') }}" icon="pos">Kasir POS</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.products') }}" icon="box">Produk</x-sidebar-link>
                <x-sidebar-link href="{{ route('cashier.shift') }}" icon="clock">Kelola Shift</x-sidebar-link>
            @else
                <x-sidebar-link href="{{ route('cashier.pos') }}" icon="pos">Kasir POS</x-sidebar-link>
                <x-sidebar-link href="{{ route('cashier.shift') }}" icon="clock">Shift Saya</x-sidebar-link>
            @endif
        </nav>

        {{-- Footer --}}
        <div class="px-3 py-4 border-t border-gray-100 dark:border-gray-800 space-y-1">
            {{-- Dark Mode Toggle --}}
            <button @click="dark = !dark"
                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium
                           text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">
                <span class="flex items-center gap-2">
                    <svg x-show="!dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="dark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-text="dark ? 'Mode Terang' : 'Mode Gelap'"></span>
                </span>
                <div :class="dark ? 'bg-brand-600' : 'bg-gray-200'"
                     class="relative w-9 h-5 rounded-full transition-colors duration-200">
                    <div :class="dark ? 'translate-x-4' : 'translate-x-0.5'"
                         class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></div>
                </div>
            </button>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-500 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-950">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()?->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_',' ', auth()->user()?->role ?? '') }}</div>
                </div>
                <div class="w-9 h-9 bg-gradient-to-br from-brand-400 to-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-soft">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
<script>
    // Livewire hook for toast notifications
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (data) => {
            window.dispatchEvent(new CustomEvent('notify', { detail: data }));
        });
    });
</script>
</body>
</html>
