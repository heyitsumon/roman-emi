<div class="container-xl py-4">

    {{-- Header --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <h2 class="fw-bold mb-0">Product Models</h2>

                @can('product-model-create')
                    <button wire:click="create" class="btn btn-primary btn-sm px-3">
                        + New Model
                    </button>
                @endcan
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session()->has('success'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4">
            {{ session('success') }}
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Per Page --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <input type="text"
                           wire:model.live="search"
                           placeholder="Search Models..."
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <div class="d-flex justify-content-md-end">
                        <select wire:model="perPage"
                                class="form-select"
                                style="max-width: 130px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">#</th>
                        <th>Product</th>
                        <th>Model Name</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Stock Value</th>
                        <th>Total Purchased</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($models as $key => $model)
                    <tr>
                        <td class="px-4">
                            <span class="text-muted small font-monospace">
                                {{ $models->firstItem() + $key }}
                            </span>
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ $model->product->product_name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            {{ $model->model_name }}
                        </td>

                        <td>
                            <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                {{ $model->qty }}
                            </span>
                        </td>

                        <td>
                            {{ number_format($model->purchase_price, 2) }}
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ number_format($model->total_value, 2) }}
                            </span>
                        </td>

                        <td>
                            {{ $model->purchases_count ?? 0 }}
                        </td>

                        <td class="text-end px-4">
                            <div class="d-flex justify-content-end gap-2">

                                @can('product-model-edit')
                                    <button wire:click="edit({{ $model->id }})"
                                            class="btn btn-sm btn-outline-warning">
                                        Edit
                                    </button>
                                @endcan

                                @can('product-model-delete')
                                    <button wire:click="delete({{ $model->id }})"
                                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                            class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                @endcan

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <span class="text-muted">
                                No models found.
                            </span>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Total Stock Value --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body py-3">
            <div class="text-end">
                <span class="text-muted me-2">
                    Total Stock Value:
                </span>

                <span class="fw-bold fs-5">
                    ৳{{ number_format($totalStockValue, 2) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $models->links() }}
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.5);"
             wire:click.self="closeModal">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="modal-header bg-light border-0 p-4">
                        <h5 class="modal-title fw-bold mb-0">
                            {{ $model_id ? 'Edit Model' : 'New Model' }}
                        </h5>

                        <button type="button"
                                class="btn-close"
                                wire:click="closeModal">
                        </button>
                    </div>

                    <div class="modal-body p-4">

                        {{-- Product --}}
                        <div class="mb-3">
                            <select wire:model="product_id"
                                    class="form-select @error('product_id') is-invalid @enderror">

                                <option value="">Select Product</option>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->product_name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('product_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Model Name --}}
                        <div class="mb-3">
                            <input type="text"
                                   wire:model="model_name"
                                   placeholder="Model Name"
                                   class="form-control @error('model_name') is-invalid @enderror">

                            @error('model_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Quantity --}}
                        <div class="mb-3">
                            <input type="number"
                                   wire:model="qty"
                                   placeholder="Quantity"
                                   class="form-control @error('qty') is-invalid @enderror"
                                   min="0">

                            @error('qty')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Purchase Price --}}
                        <div class="mb-3">
                            <input type="number"
                                   wire:model="purchase_price"
                                   placeholder="Purchase Price"
                                   class="form-control @error('purchase_price') is-invalid @enderror"
                                   min="0"
                                   step="0.01">

                            @error('purchase_price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer bg-light border-0 p-4">

                        @if($model_id)
                            <button class="btn btn-success px-4"
                                    wire:click="update">
                                Update
                            </button>
                        @else
                            <button class="btn btn-primary px-4"
                                    wire:click="store">
                                Save
                            </button>
                        @endif

                        <button class="btn btn-light border"
                                wire:click="closeModal">
                            Cancel
                        </button>

                    </div>

                </div>
            </div>
        </div>
    @endif

</div>