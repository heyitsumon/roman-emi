<div class="container py-4 py-lg-5">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                <i class="bi bi-cart-check text-primary me-2"></i>
                Purchases
            </h1>

            <p class="text-muted mb-0">
                Manage all customer purchases and EMI plans
            </p>
        </div>

        @can('purchase-create')
            <a href="{{ route('purchases.create') }}"
               wire:navigate
               class="btn btn-primary rounded-3 px-4">
                <i class="bi bi-plus-lg me-1"></i>
                New Purchase
            </a>
        @endcan

    </div>


    {{-- Search --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-3">

            <div class="input-group">

                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>

                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    class="form-control form-control-lg bg-light border-start-0"
                    placeholder="Search customer or product">

            </div>

        </div>

    </div>


    {{-- Purchase List Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 px-4 py-3">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <h5 class="fw-bold mb-1">
                        Purchase List
                    </h5>

                    <small class="text-muted">
                        Customer purchase records
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    Purchases
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 text-center">

                <thead class="table-light">

                    <tr>
                        <th class="px-3 py-3">#</th>
                        <th class="py-3 text-start">Customer</th>
                        <th class="py-3">Phone</th>
                        <th class="py-3">Location</th>
                        <th class="py-3 text-start">Product</th>
                        <th class="py-3">Model</th>
                        <th class="py-3">Price</th>
                        <th class="py-3">Down</th>
                        <th class="py-3">EMI Plan</th>
                        <th class="py-3">Actions</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($purchases as $index => $purchase)

                        <tr>

                            {{-- Customer --}}
                            <td class="fw-semibold text-muted">
                                {{ $index + 1 }}
                            </td>

                            <td class="text-start">

                                <div class="d-flex align-items-center gap-2">

                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                         style="width: 38px; height: 38px;">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <div>
                                        <div class="fw-semibold text-dark">
                                            {{ $purchase->customer->customer_name ?? 'N/A' }}
                                        </div>
                                    </div>

                                </div>

                            </td>


                            {{-- Phone --}}
                            <td>
                                <span class="text-muted">
                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $purchase->customer->customer_phone ?? 'N/A' }}
                                </span>
                            </td>


                            {{-- Location --}}
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>
                                    {{ $purchase->customer->location->name ?? 'N/A' }}
                                </span>
                            </td>


                            {{-- Product --}}
                            <td class="text-start">
                                <span class="fw-semibold">
                                    {{ $purchase->product->product_name ?? 'N/A' }}
                                </span>
                            </td>


                            {{-- Model --}}
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                    {{ $purchase->model->model_name ?? 'N/A' }}
                                </span>
                            </td>


                            {{-- Price --}}
                            <td>
                                <span class="fw-bold text-primary">
                                    {{ number_format($purchase->sales_price, 2) }} ৳
                                </span>
                            </td>


                            {{-- Down --}}
                            <td>
                                <span class="fw-semibold text-success">
                                    {{ number_format($purchase->down_price, 2) }} ৳
                                </span>
                            </td>


                            {{-- EMI --}}
                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">
                                    {{ $purchase->emi_plan }} মাস
                                </span>
                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex justify-content-center gap-2">

                                    @can('purchase-edit')

                                        <a href="{{ route('purchases.edit', $purchase) }}"
                                           class="btn btn-sm btn-outline-primary rounded-3"
                                           title="Edit">

                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-xl-inline ms-1">Edit</span>

                                        </a>

                                    @endcan


                                    @can('purchase-delete')

                                        <form method="POST"
                                              action="{{ route('purchases.destroy', $purchase) }}"
                                              class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger rounded-3"
                                                onclick="return confirm('Are you sure?')"
                                                title="Delete">

                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-xl-inline ms-1">Delete</span>

                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="py-5">

                                <div class="text-center">

                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 70px; height: 70px;">

                                        <i class="bi bi-cart-x fs-2 text-muted"></i>

                                    </div>

                                    <h6 class="fw-bold mb-1">
                                        কোনো ক্রয় পাওয়া যায়নি।
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        No purchase records found.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="card-footer bg-white border-0 px-4 py-3">

            <div class="d-flex justify-content-center">
                {{ $purchases->links() }}
            </div>

        </div>

    </div>

</div>
