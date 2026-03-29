<?php

namespace App\Livewire\SuperAdmin;

use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PasswordResetManager extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';

    // Approve modal
    public bool   $showApproveModal = false;
    public ?int   $approvingId      = null;
    public string $newPassword      = '';
    public bool   $autoGenerate     = true;

    // Reject modal
    public bool $showRejectModal = false;
    public ?int $rejectingId     = null;

    #[Computed]
    public function pendingCount(): int
    {
        return PasswordResetRequest::pending()->count();
    }

    #[Computed]
    public function requests()
    {
        return PasswordResetRequest::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn($q) => $q->whereHas('user', function ($uq) {
                $uq->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->latest('requested_at')
            ->paginate(15);
    }

    public function openApprove(int $id): void
    {
        $this->approvingId      = $id;
        $this->autoGenerate     = true;
        $this->newPassword      = Str::random(10);
        $this->showApproveModal = true;
    }

    public function generatePassword(): void
    {
        $this->newPassword = Str::random(10);
    }

    public function approve(): void
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
        ], [
            'newPassword.required' => 'Password baru wajib diisi.',
            'newPassword.min'      => 'Password baru minimal 8 karakter.',
        ]);

        $resetRequest = PasswordResetRequest::findOrFail($this->approvingId);

        // Update the user's password and set must_change_password
        $resetRequest->user->update([
            'password'             => $this->newPassword,
            'must_change_password' => true,
        ]);

        // Update the request record
        $resetRequest->update([
            'status'       => 'approved',
            'new_password' => Hash::make($this->newPassword), // Store hashed for audit
            'approved_at'  => now(),
            'approved_by'  => auth()->id(),
        ]);

        $tempPassword = $this->newPassword;

        $this->showApproveModal = false;
        $this->reset(['approvingId', 'newPassword']);
        unset($this->requests, $this->pendingCount);
        $this->dispatch('notify', type: 'success', message: "Password berhasil direset. Password sementara: {$tempPassword}");
    }

    public function openReject(int $id): void
    {
        $this->rejectingId    = $id;
        $this->showRejectModal = true;
    }

    public function reject(): void
    {
        $resetRequest = PasswordResetRequest::findOrFail($this->rejectingId);

        $resetRequest->update([
            'status'      => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        $this->showRejectModal = false;
        $this->reset('rejectingId');
        unset($this->requests, $this->pendingCount);
        $this->dispatch('notify', type: 'success', message: 'Permintaan berhasil ditolak.');
    }

    public function render()
    {
        return view('livewire.super-admin.password-reset-manager')
            ->layout('layouts.app', ['title' => 'Permintaan Reset Password']);
    }
}
