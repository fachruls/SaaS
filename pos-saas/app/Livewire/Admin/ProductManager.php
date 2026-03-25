<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductManager extends Component
{
    use WithFileUploads;

    public string $search      = '';
    public ?int   $categoryFilter = null;

    // Form fields
    public ?int    $editingId     = null;
    public string  $name          = '';
    public string  $sku           = '';
    public string  $description   = '';
    public float   $price         = 0;
    public float   $costPrice     = 0;
    public int     $stock         = 0;
    public int     $lowStockAlert = 5;
    public string  $unit          = 'pcs';
    public ?int    $categoryId    = null;
    public bool    $isActive      = true;
    public $imageFile;

    public bool $showForm    = false;
    public bool $showDeleteId = false;

    #[Computed]
    public function products()
    {
        return Product::with('category')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->categoryFilter, fn($q) => $q->byCategory($this->categoryFilter))
            ->latest()
            ->paginate(20);
    }

    #[Computed]
    public function categories()
    {
        return Category::active()->orderBy('name')->get();
    }

    public function openCreateForm(): void
    {
        $this->reset(['editingId','name','sku','description','price','costPrice','stock','lowStockAlert','unit','categoryId','isActive','imageFile']);
        $this->isActive  = true;
        $this->unit      = 'pcs';
        $this->showForm  = true;
    }

    public function openEditForm(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $this->editingId    = $product->id;
        $this->name         = $product->name;
        $this->sku          = $product->sku ?? '';
        $this->description  = $product->description ?? '';
        $this->price        = $product->price;
        $this->costPrice    = $product->cost_price;
        $this->stock        = $product->stock;
        $this->lowStockAlert = $product->low_stock_alert;
        $this->unit         = $product->unit;
        $this->categoryId   = $product->category_id;
        $this->isActive     = $product->is_active;
        $this->showForm     = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'      => ['required', 'string', 'min:2', 'max:200'],
            'price'     => ['required', 'numeric', 'min:0'],
            'stock'     => ['required', 'integer', 'min:0'],
            'imageFile' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($this->imageFile) {
            $imagePath = $this->imageFile->store('products', 'public');
        }

        $data = [
            'name'            => $this->name,
            'sku'             => $this->sku ?: null,
            'description'     => $this->description ?: null,
            'price'           => $this->price,
            'cost_price'      => $this->costPrice,
            'stock'           => $this->stock,
            'low_stock_alert' => $this->lowStockAlert,
            'unit'            => $this->unit,
            'category_id'     => $this->categoryId,
            'is_active'       => $this->isActive,
        ];
        if ($imagePath) $data['image'] = $imagePath;

        if ($this->editingId) {
            Product::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Produk berhasil diperbarui.');
        } else {
            Product::create($data);
            $this->dispatch('notify', type: 'success', message: 'Produk berhasil ditambahkan.');
        }

        $this->showForm = false;
        unset($this->products);
    }

    public function delete(int $productId): void
    {
        Product::findOrFail($productId)->delete();
        $this->dispatch('notify', type: 'success', message: 'Produk berhasil dihapus.');
        unset($this->products);
    }

    public function render()
    {
        return view('livewire.admin.product-manager')
            ->layout('layouts.app', ['title' => 'Manajemen Produk']);
    }
}
