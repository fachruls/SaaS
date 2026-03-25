<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::withCount('users', 'products', 'transactions')
            ->latest()
            ->paginate(15);
        return view('super-admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('super-admin.stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'min:2', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'currency'=> ['nullable', 'string', 'max:10'],
            // Admin account fields
            'admin_name'     => ['required', 'string', 'max:100'],
            'admin_email'    => ['required', 'email', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
        ]);

        $store = Store::create([
            'name'     => $validated['name'],
            'slug'     => Store::generateSlug($validated['name']),
            'address'  => $validated['address'] ?? null,
            'phone'    => $validated['phone'] ?? null,
            'currency' => $validated['currency'] ?? 'IDR',
            'is_active'=> true,
        ]);

        // Create the first admin user for this store
        User::create([
            'store_id' => $store->id,
            'name'     => $validated['admin_name'],
            'email'    => $validated['admin_email'],
            'password' => $validated['admin_password'], // casted to hashed
            'role'     => 'admin',
        ]);

        return redirect()
            ->route('super-admin.stores.index')
            ->with('success', "Toko \"{$store->name}\" berhasil dibuat beserta akun admin.");
    }

    public function edit(Store $store)
    {
        return view('super-admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'address'  => ['nullable', 'string', 'max:500'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'currency' => ['nullable', 'string', 'max:10'],
        ]);

        $store->update($validated);

        return redirect()
            ->route('super-admin.stores.index')
            ->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()
            ->route('super-admin.stores.index')
            ->with('success', 'Toko berhasil dihapus.');
    }

    public function toggleStatus(Store $store)
    {
        $store->update(['is_active' => ! $store->is_active]);
        $status = $store->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Toko berhasil {$status}.");
    }
}
