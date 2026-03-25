<?php

namespace App\Livewire\Cashier;

use App\Models\CashierShift;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShiftManager extends Component
{
    // ── Open Shift Form ───────────────────────────────────────────────────────
    public float  $openingBalance = 0;
    public string $openingNotes   = '';

    // ── Close Shift Form ──────────────────────────────────────────────────────
    public float  $closingBalance = 0;
    public string $closingNotes   = '';

    // ── UI State ─────────────────────────────────────────────────────────────
    public bool $showCloseModal = false;

    #[Computed]
    public function activeShift(): ?CashierShift
    {
        return CashierShift::open()
            ->where('user_id', auth()->id())
            ->with('transactions')
            ->latest()
            ->first();
    }

    #[Computed]
    public function shiftHistory()
    {
        return CashierShift::where('user_id', auth()->id())
            ->where('status', 'closed')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function openShift(): void
    {
        $this->validate([
            'openingBalance' => ['required', 'numeric', 'min:0'],
        ]);

        if ($this->activeShift) {
            $this->dispatch('notify', type: 'error', message: 'Anda masih memiliki shift yang aktif.');
            return;
        }

        CashierShift::create([
            'store_id'        => auth()->user()->store_id,
            'user_id'         => auth()->id(),
            'opening_balance' => $this->openingBalance,
            'opening_notes'   => $this->openingNotes,
            'status'          => 'open',
            'opened_at'       => now(),
        ]);

        $this->openingBalance = 0;
        $this->openingNotes   = '';
        $this->dispatch('notify', type: 'success', message: 'Shift berhasil dibuka. Selamat bekerja!');
        $this->dispatch('shift-opened');
    }

    public function openCloseModal(): void
    {
        if ($this->activeShift) {
            $this->closingBalance = $this->activeShift->opening_balance + $this->activeShift->total_sales;
        }
        $this->showCloseModal = true;
    }

    public function closeShift(): void
    {
        $this->validate([
            'closingBalance' => ['required', 'numeric', 'min:0'],
        ]);

        $shift = $this->activeShift;
        if (! $shift) {
            $this->dispatch('notify', type: 'error', message: 'Tidak ada shift aktif.');
            return;
        }

        $shift->update([
            'closing_balance' => $this->closingBalance,
            'closing_notes'   => $this->closingNotes,
            'status'          => 'closed',
            'closed_at'       => now(),
        ]);

        $this->showCloseModal = false;
        $this->dispatch('notify', type: 'success', message: 'Shift berhasil ditutup.');
    }

    public function render()
    {
        return view('livewire.cashier.shift-manager')
            ->layout('layouts.app');
    }
}
