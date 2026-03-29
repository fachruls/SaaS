<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function save(): void
    {
        $this->validate([
            'current_password'          => ['required'],
            'new_password'              => ['required', 'string', 'min:8', 'regex:/[a-zA-Z]/', 'regex:/[0-9]/', 'confirmed'],
            'new_password_confirmation' => ['required'],
        ], [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.regex'     => 'Password baru harus mengandung huruf dan angka.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        // Verify current password
        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password lama tidak sesuai.');
            return;
        }

        // Update password
        $user->update([
            'password'             => $this->new_password,
            'must_change_password' => false,
        ]);

        // Invalidate all other sessions
        Auth::logoutOtherDevices($this->new_password);

        // Logout current session
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Redirect to login
        $this->redirect(route('login'), navigate: false);

        session()->flash('status', 'Password berhasil diubah. Silakan login kembali.');
    }

    public function render()
    {
        return view('livewire.auth.change-password')
            ->layout('layouts.app', ['title' => 'Ubah Password']);
    }
}
