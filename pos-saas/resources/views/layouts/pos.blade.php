<!DOCTYPE html>
<html lang="id"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
    x-init="$watch('dark', v => {
        localStorage.setItem('theme', v ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', v);
    }); document.documentElement.classList.toggle('dark', dark);"
    :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir POS — {{ auth()->user()?->store?->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-white antialiased h-screen overflow-hidden transition-colors duration-300">

{{-- Toast Notification (same as main layout) --}}
<div
    x-data="{
        notifications: [],
        add(event) {
            let n = { id: Date.now(), type: event.detail.type, message: event.detail.message };
            this.notifications.push(n);
            setTimeout(() => this.remove(n.id), 4000);
        },
        remove(id) { this.notifications = this.notifications.filter(n => n.id !== id); }
    }"
    @notify.window="add($event)"
    class="fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full pointer-events-none">
    <template x-for="n in notifications" :key="n.id">
        <div x-show="n.show !== false" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             :class="{
                'bg-emerald-50 border-emerald-200 text-emerald-800': n.type === 'success',
                'bg-red-50 border-red-200 text-red-800': n.type === 'error',
                'bg-amber-50 border-amber-200 text-amber-800': n.type === 'warning',
             }"
             class="flex items-center gap-2 px-4 py-2.5 rounded-xl border shadow-soft text-sm font-medium pointer-events-auto">
            <span x-text="n.message"></span>
        </div>
    </template>
</div>

{{ $slot }}

@livewireScripts
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (data) => {
            window.dispatchEvent(new CustomEvent('notify', { detail: data }));
        });
    });
</script>
</body>
</html>
