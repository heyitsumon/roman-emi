<div class="container-xl py-4">

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('message') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Add / Edit Modal --}}
    @if ($isOpen)
        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.5);"
             wire:click.self="closeModal">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="modal-header bg-primary text-white px-4 py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-person-plus me-2"></i>
                            {{ $updateMode ? 'Edit Customer' : 'Add Customer' }}
                        </h5>

                        <button type="button"
                                class="btn-close btn-close-white"
                                wire:click="closeModal">
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body p-4">

                        <form>

                            {{-- Name & Customer ID --}}
                            <div class="row g-3 mb-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Customer Name
                                    </label>

                                    <input type="text"
                                           wire:model="customer_name"
                                           placeholder="Enter customer name"
                                           class="form-control @error('customer_name') is-invalid @enderror">

                                    @error('customer_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Customer ID
                                    </label>

                                    <input type="text"
                                           wire:model="customer_id"
                                           placeholder="Enter customer ID"
                                           class="form-control @error('customer_id') is-invalid @enderror">

                                    @error('customer_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>


                            {{-- Phones --}}
                            <div class="row g-3 mb-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Phone
                                    </label>

                                    <input type="text"
                                           wire:model="customer_phone"
                                           placeholder="Phone number"
                                           class="form-control @error('customer_phone') is-invalid @enderror">

                                    @error('customer_phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Phone 2
                                        <span class="text-muted fw-normal">(Optional)</span>
                                    </label>

                                    <input type="text"
                                           wire:model="customer_phone2"
                                           placeholder="Alternative phone"
                                           class="form-control">
                                </div>

                            </div>


                            {{-- Landlord & Location --}}
                            <div class="row g-3 mb-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Landlord Name
                                        <span class="text-muted fw-normal">(Optional)</span>
                                    </label>

                                    <input type="text"
                                           wire:model="landlord_name"
                                           placeholder="Landlord name"
                                           class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Location
                                    </label>

                                    <select wire:model="location_id"
                                            class="form-select @error('location_id') is-invalid @enderror">

                                        <option value="">Select Location</option>

                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">
                                                {{ $location->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('location_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>


                            {{-- Location Details --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Location Details
                                    <span class="text-muted fw-normal">(Optional)</span>
                                </label>

                                <input type="text"
                                       wire:model="location_details"
                                       placeholder="House, road, area etc."
                                       class="form-control">

                            </div>


                            {{-- File Upload & Preview --}}
                            <div class="row g-3 align-items-center">

                                <div class="col-md-7">

                                    <label class="form-label fw-semibold">
                                        Customer Image
                                    </label>

                                    <input type="file"
                                           wire:model="customer_image"
                                           class="form-control"
                                           accept="image/*">

                                </div>

                                @if ($customer_image)

                                    <div class="col-md-5 text-center">

                                        <img src="{{ $customer_image->temporaryUrl() }}"
                                             alt="Preview"
                                             class="rounded-circle shadow-sm border"
                                             style="width:110px;height:110px;object-fit:cover;">

                                    </div>

                                @endif

                            </div>

                        </form>

                    </div>


                    {{-- Footer --}}
                    <div class="modal-footer bg-light px-4 py-3">

                        <button wire:click="closeModal"
                                type="button"
                                class="btn btn-light border px-4">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel
                        </button>

                        @if ($updateMode)

                            <button wire:click="update"
                                    type="button"
                                    class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>
                                Update
                            </button>

                        @else

                            <button wire:click="store"
                                    type="button"
                                    class="btn btn-success px-4">
                                <i class="bi bi-person-plus me-1"></i>
                                Add Customer
                            </button>

                        @endif

                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- Search / Actions --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">

            <div class="row g-3 align-items-center">

                {{-- Search --}}
                <div class="col-lg-4 col-md-6">

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               wire:model.live="search"
                               placeholder="Search Customers..."
                               class="form-control border-start-0">

                    </div>

                </div>


                {{-- Search Result --}}
                <div class="col-lg-3 col-md-6">

                    @if ($search)

                        <div class="text-muted small">
                            <i class="bi bi-search me-1"></i>

                            <strong>Search Result:</strong>
                            {{ $customers->total() }} item(s) found.
                        </div>

                    @endif

                </div>


                {{-- New Customer --}}
                @can('customer-create')

                    <div class="col-lg-auto ms-lg-auto">

                        <button wire:click="create"
                                class="btn btn-primary px-4 shadow-sm">

                            <i class="bi bi-plus-lg me-1"></i>
                            New Customer

                        </button>

                    </div>

                @endcan


              

                {{-- Per Page --}}
                <div class="col-lg-auto">

                    <select wire:model.live="perPage"
                            class="form-select">

                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>

                    </select>

                </div>

            </div>

        </div>
    </div>


    {{-- Trash Toggle --}}
    @role('admin')

        <div class="d-flex justify-content-between align-items-center mb-3">

            <button wire:click="toggleTrash"
                    class="btn btn-outline-secondary btn-sm">

                <i class="bi bi-trash me-1"></i>

                {{ $showDeleted ? 'Show Active Customers' : 'Show Trash' }}

            </button>

        </div>

    @endrole


    {{-- Customers Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <h5 class="mb-1 fw-bold">
                        <i class="bi bi-people text-primary me-2"></i>
                        Customers
                    </h5>

                    <small class="text-muted">
                        Customer management
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    {{ $customers->total() }} Customers
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4">#Id</th>
                        <th>Name</th>
                        <th>Customer ID</th>
                        <th>Phone</th>
                        <th>Landlord</th>
                        <th>Location</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th class="text-end px-4">Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($customers as $customer)

                        <tr @if ($customer->trashed()) class="table-secondary opacity-75" @endif>

                            <td class="px-4 fw-semibold">
                                {{ $customer->id }}
                            </td>


                            <td>

                                <a target="_blank"
                                   href="{{ route('customers.emi_plans', $customer->id) }}"
                                   class="text-decoration-none fw-semibold text-primary">

                                    {{ $customer->customer_name }}

                                </a>

                            </td>


                            <td>

                                <a target="_blank"
                                   href="{{ route('report.print', $customer->id) }}"
                                   class="text-decoration-none">

                                    {{ $customer->customer_id }}

                                </a>

                            </td>


                            <td>

                                <a href="tel:{{ $customer->customer_phone }}"
                                   class="text-decoration-none text-primary">

                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $customer->customer_phone }}

                                </a>

                                @if ($customer->customer_phone2)

                                    <br>

                                    <a href="tel:{{ $customer->customer_phone2 }}"
                                       class="text-decoration-none text-primary">

                                        <i class="bi bi-telephone me-1"></i>
                                        {{ $customer->customer_phone2 }}

                                    </a>

                                @endif

                            </td>


                            <td>
                                {{ $customer->landlord_name ?: '-' }}
                            </td>


                            <td>
                                {{ $customer->location->name ?? '-' }}
                            </td>


                            {{-- Image --}}
                            <td>

                                @if ($customer->customer_image && file_exists(public_path('storage/' . $customer->customer_image)))

                                    <img src="{{ asset('storage/' . $customer->customer_image) }}"
                                         alt="{{ $customer->customer_name }}"
                                         loading="lazy"
                                         wire:click="openModal({{ $customer->id }})"
                                         class="rounded-circle shadow-sm border"
                                         style="width:48px;height:48px;object-fit:cover;cursor:pointer;">

                                @else

                                    <div wire:click="openModal({{ $customer->id }})"
                                         class="rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-secondary"
                                         style="width:48px;height:48px;cursor:pointer;">

                                        {{ strtoupper(substr($customer->customer_name, 0, 1)) }}

                                    </div>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if ($customer->trashed())

                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">
                                        <i class="bi bi-trash me-1"></i>
                                        Deleted
                                    </span>

                                @else

                                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Active
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end px-4">

                                <div class="d-flex justify-content-end gap-1 flex-wrap">

                                    @if ($customer->trashed())

                                        @can('customer-edit')

                                            <button wire:click="restore({{ $customer->id }})"
                                                    class="btn btn-sm btn-success">

                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                Restore

                                            </button>

                                        @endcan


                                        @can('customer-delete')

                                            <button wire:click="forceDelete({{ $customer->id }})"
                                                    wire:confirm="Permanently delete this customer?"
                                                    class="btn btn-sm btn-danger">

                                                <i class="bi bi-trash"></i>
                                                Delete Permanently

                                            </button>

                                        @endcan

                                    @else

                                        @can('customer-edit')

                                            <button wire:click="edit({{ $customer->id }})"
                                                    class="btn btn-sm btn-warning">

                                                <i class="bi bi-pencil-square"></i>
                                                Edit

                                            </button>

                                        @endcan


                                        @can('customer-delete')

                                            <button wire:click="delete({{ $customer->id }})"
                                                    wire:confirm="Move this customer to trash?"
                                                    class="btn btn-sm btn-danger">

                                                <i class="bi bi-trash"></i>
                                                Delete

                                            </button>

                                        @endcan

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bi bi-people fs-1 d-block mb-2"></i>

                                    <h6 class="fw-semibold">
                                        No customers found
                                    </h6>

                                    <small>
                                        Try changing your search.
                                    </small>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    <div class="mt-4">

        {{ $customers->links() }}

    </div>


    {{-- Customer Information Modal --}}
    @if ($showModal && $viewCustomerData)

        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.55);"
             wire:click.self="closeModal">

            <div class="modal-dialog modal-dialog-centered modal-lg">

                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="modal-header bg-primary text-white px-4 py-3">

                        <h5 class="modal-title fw-bold">

                            <i class="bi bi-person-vcard me-2"></i>
                            Customer Information

                        </h5>

                        <button wire:click="closeModal"
                                type="button"
                                class="btn-close btn-close-white">
                        </button>

                    </div>


                    {{-- Body --}}
                    <div class="modal-body p-4">

                        <div class="row g-4 align-items-center">

                            {{-- Customer Info --}}
                            <div class="col-md-7">

                                <h3 class="fw-bold mb-4">
                                    {{ $viewCustomerData->customer_name }}
                                </h3>


                                <div class="list-group list-group-flush">

                                    <div class="list-group-item px-0">
                                        <strong>Customer ID:</strong>
                                        <span class="text-muted ms-2">
                                            {{ $viewCustomerData->customer_id }}
                                        </span>
                                    </div>


                                    <div class="list-group-item px-0">

                                        <strong>Phone:</strong>

                                        <a href="tel:{{ $viewCustomerData->customer_phone }}"
                                           class="btn btn-sm btn-outline-primary ms-2">

                                            <i class="bi bi-telephone me-1"></i>
                                            {{ $viewCustomerData->customer_phone }}

                                        </a>

                                    </div>


                                    @if ($viewCustomerData->customer_phone2)

                                        <div class="list-group-item px-0">

                                            <strong>Phone 2:</strong>

                                            <a href="tel:{{ $viewCustomerData->customer_phone2 }}"
                                               class="btn btn-sm btn-outline-primary ms-2">

                                                <i class="bi bi-telephone me-1"></i>
                                                {{ $viewCustomerData->customer_phone2 }}

                                            </a>

                                        </div>

                                    @endif


                                    <div class="list-group-item px-0">

                                        <strong>Location:</strong>

                                        <span class="text-muted ms-2">
                                            {{ $viewCustomerData->location->name ?? '-' }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- Customer Image --}}
                            <div class="col-md-5 text-center">

                                @if ($viewCustomerData->customer_image && file_exists(public_path('storage/' . $viewCustomerData->customer_image)))

                                    <img loading="lazy"
                                         src="{{ asset('storage/' . $viewCustomerData->customer_image) }}"
                                         alt="Customer Image"
                                         class="rounded-circle shadow border"
                                         style="width:190px;height:190px;object-fit:cover;">

                                @else

                                    <div class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center text-secondary fw-bold fs-1 shadow-sm"
                                         style="width:190px;height:190px;">

                                        {{ strtoupper(substr($viewCustomerData->customer_name, 0, 1)) }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="modal-footer bg-light">

                        <button wire:click="closeModal"
                                class="btn btn-secondary px-4">

                            <i class="bi bi-x-lg me-1"></i>
                            Close

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>