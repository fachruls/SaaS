<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\StoreController as SuperAdminStoreController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Livewire\Cashier\PosTerminal;
use App\Livewire\Cashier\ShiftManager;
use App\Livewire\Admin\ProductManager;
use Illuminate\Support\Facades\Route;

// ── Root & Auth ────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isSuperAdmin()) return redirect()->route('super-admin.dashboard');
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isCashier()) return redirect()->route('cashier.pos');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Super Admin Panel ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('super-admin.dashboard');
        })->name('dashboard');

        Route::resource('stores', SuperAdminStoreController::class);
        Route::patch('stores/{store}/toggle', [SuperAdminStoreController::class, 'toggleStatus'])
            ->name('stores.toggle');
        Route::resource('stores.users', UserController::class)
            ->shallow();
    });

// ── Admin Panel ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products managed via Livewire
        Route::get('/products', ProductManager::class)->name('products');

        // Shift management
        Route::get('/shifts', ShiftManager::class)->name('shifts');

        // Users (store employees)
        Route::resource('users', UserController::class)->except(['show']);
    });

// ── Cashier POS ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,cashier'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {
        Route::get('/pos', PosTerminal::class)->name('pos');
        Route::get('/shift', ShiftManager::class)->name('shift');
    });
