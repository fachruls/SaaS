<?php

namespace App\Livewire\Cashier;

use App\Models\CashierShift;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class PosTerminal extends Component
{
    // ── Search & Filter ───────────────────────────────────────────────────────
    public string  $search          = '';
    public ?int    $selectedCategory = null;

    // ── Cart ─────────────────────────────────────────────────────────────────
    public array $cart = [];

    // ── Checkout ─────────────────────────────────────────────────────────────
    public string  $paymentMethod    = 'cash';
    public float   $amountPaid       = 0;
    public float   $discountPercent  = 0;
    public float   $taxPercent       = 0;
    public string  $paymentReference = '';
    public string  $notes            = '';

    // ── UI State ─────────────────────────────────────────────────────────────
    public bool    $showCheckoutModal = false;
    public bool    $showSuccessModal  = false;
    public ?int    $lastTransactionId = null;

    // ── Shift ─────────────────────────────────────────────────────────────────
    public ?CashierShift $activeShift = null;

    // ─── Lifecycle ────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->activeShift = CashierShift::open()
            ->where('user_id', auth()->id())
            ->latest()
            ->first();
    }

    // ─── Computed Properties ──────────────────────────────────────────────────

    #[Computed]
    public function products()
    {
        return Product::active()
            ->with('category')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->selectedCategory, fn($q) => $q->byCategory($this->selectedCategory))
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::active()->orderBy('name')->get();
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function discountAmount(): float
    {
        return $this->subtotal * ($this->discountPercent / 100);
    }

    #[Computed]
    public function taxableAmount(): float
    {
        return $this->subtotal - $this->discountAmount;
    }

    #[Computed]
    public function taxAmount(): float
    {
        return $this->taxableAmount * ($this->taxPercent / 100);
    }

    #[Computed]
    public function total(): float
    {
        return $this->taxableAmount + $this->taxAmount;
    }

    #[Computed]
    public function changeAmount(): float
    {
        if ($this->paymentMethod === 'cash') {
            return max(0, $this->amountPaid - $this->total);
        }
        return 0;
    }

    #[Computed]
    public function cartItemCount(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    // ─── Cart Management ──────────────────────────────────────────────────────

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (! $product || ! $product->is_active) {
            $this->dispatch('notify', type: 'error', message: 'Produk tidak tersedia.');
            return;
        }

        if ($product->isOutOfStock()) {
            $this->dispatch('notify', type: 'error', message: "Stok {$product->name} habis!");
            return;
        }

        // Check if adding one more would exceed stock
        $currentQty = $this->cart[$productId]['quantity'] ?? 0;
        if ($currentQty >= $product->stock) {
            $this->dispatch('notify', type: 'warning', message: "Stok {$product->name} hanya tersisa {$product->stock}.");
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'sku'      => $product->sku,
                'price'    => $product->price,
                'stock'    => $product->stock,
                'image'    => $product->image_url,
                'quantity' => 1,
            ];
        }

        $this->dispatch('notify', type: 'success', message: "{$product->name} ditambahkan.");
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        $product = Product::find($productId);
        if ($product && $quantity > $product->stock) {
            $this->dispatch('notify', type: 'warning', message: "Stok maksimal: {$product->stock}.");
            $quantity = $product->stock;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] = $quantity;
        }
    }

    public function clearCart(): void
    {
        $this->cart            = [];
        $this->discountPercent = 0;
        $this->amountPaid      = 0;
        $this->paymentMethod   = 'cash';
        $this->paymentReference = '';
        $this->notes           = '';
    }

    // ─── Checkout ─────────────────────────────────────────────────────────────

    public function openCheckout(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', type: 'error', message: 'Keranjang masih kosong.');
            return;
        }

        if (! $this->activeShift) {
            $this->dispatch('notify', type: 'error', message: 'Buka shift terlebih dahulu sebelum bertransaksi.');
            return;
        }

        $this->amountPaid       = $this->total;
        $this->showCheckoutModal = true;
    }

    public function processCheckout(): void
    {
        $this->validate([
            'paymentMethod' => ['required', 'in:cash,qris,transfer,card'],
            'amountPaid'    => ['required_if:paymentMethod,cash', 'numeric', 'min:0'],
        ]);

        if ($this->paymentMethod === 'cash' && $this->amountPaid < $this->total) {
            $this->addError('amountPaid', 'Uang yang diterima kurang dari total belanja.');
            return;
        }

        try {
            DB::transaction(function () {
                $transaction = Transaction::create([
                    'store_id'          => auth()->user()->store_id,
                    'cashier_shift_id'  => $this->activeShift?->id,
                    'user_id'           => auth()->id(),
                    'invoice_number'    => Transaction::generateInvoiceNumber(auth()->user()->store_id),
                    'subtotal'          => $this->subtotal,
                    'discount_percent'  => $this->discountPercent,
                    'discount_amount'   => $this->discountAmount,
                    'tax_percent'       => $this->taxPercent,
                    'tax_amount'        => $this->taxAmount,
                    'total'             => $this->total,
                    'payment_method'    => $this->paymentMethod,
                    'amount_paid'       => $this->paymentMethod === 'cash' ? $this->amountPaid : $this->total,
                    'change_amount'     => $this->changeAmount,
                    'payment_reference' => $this->paymentReference,
                    'notes'             => $this->notes,
                    'status'            => 'completed',
                ]);

                foreach ($this->cart as $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $item['id'],
                        'product_name'   => $item['name'],
                        'product_sku'    => $item['sku'],
                        'price'          => $item['price'],
                        'quantity'       => $item['quantity'],
                        'discount'       => 0,
                        'subtotal'       => $item['price'] * $item['quantity'],
                    ]);

                    // Decrement stock
                    Product::withoutGlobalScopes()->find($item['id'])?->decrementStock($item['quantity']);
                }

                // Update shift totals
                if ($this->activeShift) {
                    $this->activeShift->increment('total_sales', $this->total);
                    $this->activeShift->increment('total_transactions');
                }

                $this->lastTransactionId = $transaction->id;
            });

            $this->clearCart();
            $this->showCheckoutModal = false;
            $this->showSuccessModal  = true;

        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'error', message: 'Transaksi gagal: '.$e->getMessage());
        }
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal  = false;
        $this->lastTransactionId = null;
    }

    // ─── Category Filter ──────────────────────────────────────────────────────

    public function filterByCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->search           = '';
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.cashier.pos-terminal')
            ->layout('layouts.pos');
    }
}
