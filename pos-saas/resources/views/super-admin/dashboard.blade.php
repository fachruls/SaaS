<x-app-layout title="Dashboard Super Admin">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-soft-lg border-0">
                <div class="text-white/80 text-sm font-semibold mb-1">Total Toko (Tenant)</div>
                <div class="text-3xl font-black mb-1">{{ \App\Models\Store::count() }}</div>
                <div class="text-xs text-white/70">Terdaftar di sistem</div>
            </div>
            
            <div class="card p-6 bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">Total Pengguna</div>
                <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ \App\Models\User::whereNotNull('store_id')->count() }}</div>
                <div class="text-xs text-gray-400">Kasir & Admin</div>
            </div>

            <div class="card p-6 bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">Total Transaksi (Global)</div>
                <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ \App\Models\Transaction::withoutGlobalScopes()->count() }}</div>
                <div class="text-xs text-emerald-500 font-medium">Platform Activity</div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Selamat Datang, {{ auth()->user()->name }}!</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Anda berada di Panel Super Admin. Di sini Anda memiliki akses penuh untuk mendaftarkan dan mengelola seluruh tenant (toko) yang terdaftar dalam platform Sellvix ini.
            </p>
            <a href="{{ route('super-admin.stores.index') }}" class="btn-primary">
                Kelola Toko Sekarang
            </a>
        </div>
    </div>
</x-app-layout>
