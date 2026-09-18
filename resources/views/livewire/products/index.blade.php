<div class="container-xl py-4">

    {{-- Header Section --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                <div>
                    <h2 class="fw-bold mb-1">Products</h2>
                    <p class="text-muted small mb-0">
                        Manage your inventory and associated product models
                    </p>
                </div>

                @can('product-create')
                    <button wire:click="create"
                            class="btn btn-primary px-4 shadow-sm">
                        New Product
                    </button>
                @endcan

            </div>

        </div>
    </div>


    {{-- Notifications --}}
    @if (session()->has('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4"
             role="alert">

            <span>{{ session('success') }}</span>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @elseif (session()->has('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4"
             role="alert">

            <span>{{ session('error') }}</span>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-3">

            <div class="row g-3 align-items-center">

                <div class="col-md-8">

                    <input type="text"
                           wire:model.live="search"
                           placeholder="Search product name or model..."
                           class="form-control">

                </div>


                <div class="col-md-4">

                    <div class="d-flex align-items-center justify-content-md-end gap-2">

                        <span class="text-muted small fw-semibold">
                            Show:
                        </span>

                        <select wire:model.live="perPage"
                                class="form-select"
                                style="width: 100px;">

                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                            <option value="50">50</option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Data Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        Products
                    </h5>

                    <small class="text-muted">
                        Product and associated model list
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    {{ $products->total() }}
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4" style="width: 80px;">
                            Rank
                        </th>

                        <th>
                            Product Details
                        </th>

                        <th>
                            Associated Models
                        </th>

                        <th class="text-end px-4">
                            Manage
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($products as $key => $product)

                        <tr>

                            <td class="px-4">

                                <span class="text-muted small font-monospace">
                                    #{{ str_pad($products->firstItem() + $key, 2, '0', STR_PAD_LEFT) }}
                                </span>

                            </td>


                            <td>

                                <div class="fw-bold">
                                    {{ $product->product_name }}
                                </div>

                            </td>


                            <td>

                                <div class="d-flex flex-wrap gap-2">

                                    @forelse($product->models as $model)

                                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-normal">

                                            {{ $model->model_name }}

                                        </span>

                                    @empty

                                        <span class="text-muted small fst-italic">
                                            Unassigned
                                        </span>

                                    @endforelse

                                </div>

                            </td>


                            <td class="text-end px-4">

                                <div class="d-flex justify-content-end gap-2">

                                    @can('product-edit')

                                        <button wire:click="edit({{ $product->id }})"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Edit Product">

                                            Edit

                                        </button>

                                    @endcan


                                    @can('product-delete')

                                        <button wire:click="delete({{ $product->id }})"
                                                onclick="confirm('Delete this product and its models?') || event.stopImmediatePropagation()"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete Product">

                                            Delete

                                        </button>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-5">

                                <div class="text-muted">

                                    <p class="fw-semibold mb-1">
                                        No results found for "{{ $search }}"
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination Section --}}
    <div class="mt-4">

        {{ $products->links() }}

    </div>


    {{-- Modal --}}
    @if ($isOpen)

        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.5);"
             wire:click.self="closeModal">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Modal Header --}}
                    <div class="modal-header bg-light border-0 p-4">

                        <div>

                            <h5 class="modal-title fw-bold mb-1">
                                {{ $product_id ? 'Update Product' : 'Create New Product' }}
                            </h5>

                            <p class="text-muted small mb-0">
                                Enter the details below to save the product.
                            </p>

                        </div>

                        <button type="button"
                                class="btn-close"
                                wire:click="closeModal">
                        </button>

                    </div>


                    {{-- Modal Body --}}
                    <div class="modal-body p-4">

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Product Name
                            </label>

                            <input type="text"
                                   wire:model="product_name"
                                   placeholder="e.g. Samsung Galaxy"
                                   class="form-control @error('product_name') is-invalid @enderror">

                            @error('product_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-white border-0 p-4">

                        <button class="btn btn-light border"
                                wire:click="closeModal">

                            Cancel

                        </button>


                        @if ($product_id)

                            <button class="btn btn-success px-4"
                                    wire:click="update">

                                Update Changes

                            </button>

                        @else

                            <button class="btn btn-primary px-4"
                                    wire:click="store">

                                Confirm Save

                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>