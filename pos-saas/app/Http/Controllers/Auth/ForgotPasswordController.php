<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check if there is already a pending request
            $existingPending = PasswordResetRequest::forUser($user->id)->pending()->exists();

            if (! $existingPending) {
                PasswordResetRequest::create([
                    'user_id'      => $user->id,
                    'status'       => 'pending',
                    'requested_at' => now(),
                ]);
            }
        }

        // Always show safe message (don't leak user existence)
        return redirect()->route('forgot-password')
            ->with('status', 'Jika email terdaftar dalam sistem, permintaan reset password akan diproses. Silakan hubungi Super Admin untuk informasi lebih lanjut.');
    }

    public function showStatusForm()
    {
        return view('auth.reset-status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        $latestRequest = null;
        if ($user) {
            $latestRequest = PasswordResetRequest::forUser($user->id)
                ->latest('requested_at')
                ->first();
        }

        return view('auth.reset-status', [
            'searched' => true,
            'email'    => $request->email,
            'request'  => $latestRequest,
        ]);
    }
}
