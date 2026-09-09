<div class="p-4 max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-2xl font-bold">Purchases</h1>
        <a href="{{ route('purchases.create') }}" wire:navigate class="btn btn-primary">New Purchase</a>
    </div>

    <input wire:model.live.debounce.300ms="search" type="search" class="input input-bordered mb-6 w-full" placeholder="Search customer or product">

    {{-- Purchase List Table --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Product</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Down</th>
                    <th>EMI Plan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $index => $purchase)
                    <tr>
                        {{-- <td>{{ $purchases->firstItem() + $index }}</td> --}}
                        <td>{{ $purchase->customer->customer_name ?? 'N/A' }}</td>
                        <td>{{ $purchase->customer->customer_phone ?? 'N/A' }}</td>
                        <td>{{ $purchase->customer->location->name ?? 'N/A' }}</td>
                        <td>{{ $purchase->product->product_name ?? 'N/A' }}</td>
                        <td>{{ $purchase->model->model_name ?? 'N/A' }}</td>
                        <td>{{ number_format($purchase->sales_price, 2) }} ৳</td>
                        <td>{{ number_format($purchase->down_price, 2) }} ৳</td>
                        <td>{{ $purchase->emi_plan }} মাস</td>
                        <td class="space-x-1">
                            <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('purchases.destroy', $purchase) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">কোনো ক্রয় পাওয়া যায়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $purchases->links() }}
        </div>
    </div>
</div>
