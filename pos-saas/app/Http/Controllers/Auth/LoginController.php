<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();

            if (! $user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectBasedOnRole($user)
    {
        return match($user->role) {
            'super_admin' => redirect()->route('super-admin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'cashier'     => redirect()->route('cashier.pos'),
            default       => redirect('/'),
        };
    }
}
