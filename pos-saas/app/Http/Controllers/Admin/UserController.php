<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('store_id', auth()->user()->store_id)
            ->whereIn('role', ['admin', 'cashier'])
            ->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'in:admin,cashier'],
        ]);

        User::create([
            'store_id' => auth()->user()->store_id,
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_if($user->store_id !== auth()->user()->store_id, 403);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->store_id !== auth()->user()->store_id, 403);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'role'     => ['required', 'in:admin,cashier'],
            'is_active'=> ['boolean'],
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'Pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->store_id !== auth()->user()->store_id, 403);
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa menghapus diri sendiri.');
        $user->delete();
        return back()->with('success', 'Pengguna dihapus.');
    }
}
