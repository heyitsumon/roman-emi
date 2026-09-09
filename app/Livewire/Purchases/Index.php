<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Purchase;
use Livewire\WithPagination;


class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 25;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = trim($this->search);

        $purchases = Purchase::query()
            ->with(['customer.location', 'product', 'model'])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery
                        ->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_id', 'like', "%{$search}%");
                })->orWhereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('product_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.purchases.index', compact('purchases'));
    }

}
