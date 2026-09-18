<div class="container py-4 py-lg-5">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge rounded-pill text-bg-primary px-3 py-2">
                    {{ __('ui.purchases') }}
                </span>
            </div>

            <h1 class="fw-bold mb-1 text-dark">
                {{ __('ui.create_purchase') }}
            </h1>

            <p class="text-muted mb-0">
                {{ __('ui.add_product_emi') }}
            </p>
        </div>

        <a href="{{ route('purchases.index') }}"
           wire:navigate
           class="btn btn-light border rounded-3 px-4">
            <i class="bi bi-arrow-left me-1"></i>
            {{ __('ui.cancel') }}
        </a>

    </div>


    {{-- Form Error --}}
    @if ($errors->has('form'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first('form') }}
        </div>
    @endif


    <form wire:submit="store">

        {{-- Customer + Product --}}
        <div class="row g-4 mb-4">

            {{-- Customer --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <div class="d-flex align-items-center gap-3">

                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-person fs-5"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    {{ __('ui.customer') }}
                                </h5>

                                <small class="text-muted">
                                    Select a customer for this purchase
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="card-body p-4">

                        <label class="form-label fw-semibold">
                            {{ __('ui.customer') }}
                        </label>

                        <div class="position-relative">

                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>

                                <input
                                    wire:model.live.debounce.300ms="customerSearch"
                                    type="search"
                                    class="form-control form-control-lg bg-light border-start-0"
                                    placeholder="Search or select a customer"
                                    autocomplete="off">
                            </div>


                            {{-- Customer Dropdown --}}
                            @if ($customerSearch !== '' && $customer_id === null)

                                <div class="position-absolute start-0 end-0 mt-2 bg-white border rounded-3 shadow-lg overflow-auto"
                                     style="z-index: 1050; max-height: 250px;">

                                    @forelse ($customers as $customer)

                                        <button
                                            type="button"
                                            wire:click="selectCustomer({{ $customer->id }})"
                                            class="btn btn-white w-100 text-start border-0 rounded-0 px-3 py-3">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <div>
                                                    <div class="fw-semibold text-dark">
                                                        {{ $customer->customer_id }}
                                                    </div>

                                                    <small class="text-muted">
                                                        {{ $customer->customer_name }}
                                                    </small>
                                                </div>

                                                <i class="bi bi-chevron-right text-primary"></i>

                                            </div>

                                        </button>

                                    @empty

                                        <div class="text-center text-muted py-4">
                                            <i class="bi bi-person-x fs-4 d-block mb-2"></i>
                                            No customers found.
                                        </div>

                                    @endforelse

                                </div>

                            @endif

                        </div>


                        @if ($customer_id !== null)
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Customer selected
                                </span>
                            </div>
                        @endif

                        @error('customer_id')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

            </div>


            {{-- Product --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-header bg-white border-0 px-4 pt-4">

                        <div class="d-flex align-items-center gap-3">

                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-box-seam fs-5"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    {{ __('ui.product') }}
                                </h5>

                                <small class="text-muted">
                                    Select product and available model
                                </small>
                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        {{-- Product --}}
                        <label class="form-label fw-semibold">
                            {{ __('ui.product') }}
                        </label>

                        <select
                            wire:model.live="product_id"
                            class="form-select form-select-lg mb-4">

                            <option value="">
                                {{ __('ui.choose_product') }}
                            </option>

                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->product_name }}
                                </option>
                            @endforeach

                        </select>

                        @error('product_id')
                            <div class="text-danger small mt-n3 mb-3">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- Model --}}
                        <label class="form-label fw-semibold">
                            {{ __('ui.available_model') }}
                        </label>

                        <select
                            wire:model="model_id"
                            class="form-select form-select-lg"
                            @disabled(empty($models))>

                            <option value="">
                                {{ empty($models) ? __('ui.choose_product_first') : __('ui.choose_model') }}
                            </option>

                            @foreach ($models as $model)
                                <option value="{{ $model['id'] }}">
                                    {{ $model['model_name'] }} ({{ $model['qty'] }} available)
                                </option>
                            @endforeach

                        </select>

                        @error('model_id')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Plan --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 px-4 pt-4">

                <div class="d-flex align-items-center gap-3">

                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="bi bi-credit-card fs-5"></i>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            {{ __('ui.payment_plan') }}
                        </h5>

                        <small class="text-muted">
                            Configure pricing and EMI details
                        </small>
                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- Shop Owner Price --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label fw-semibold">
                            Shop Owner Price (Cost)
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">
                                ৳
                            </span>

                            <input
                                wire:model="sales_price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control form-control-lg"
                                placeholder="800">

                        </div>

                        <div class="form-text">
                            Your item cost / sales price
                        </div>

                        @error('sales_price')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Customer Net Price --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label fw-semibold">
                            Customer Net Price (Total)
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">
                                ৳
                            </span>

                            <input
                                wire:model="net_price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control form-control-lg"
                                placeholder="1000">

                        </div>

                        <div class="form-text">
                            Customer pays this total
                        </div>

                        @error('net_price')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Down Payment --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label fw-semibold">
                            {{ __('ui.down_payment') }}
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">
                                ৳
                            </span>

                            <input
                                wire:model="down_price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control form-control-lg">

                        </div>

                        @error('down_price')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- EMI Months --}}
                    <div class="col-md-6 col-lg-3">

                        <label class="form-label fw-semibold">
                            {{ __('ui.emi_months') }}
                        </label>

                        <div class="input-group">

                            <input
                                wire:model="emi_plan"
                                type="number"
                                min="1"
                                class="form-control form-control-lg">

                            <span class="input-group-text bg-light">
                                Months
                            </span>

                        </div>

                        @error('emi_plan')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">

            <a
                href="{{ route('purchases.index') }}"
                wire:navigate
                class="btn btn-light border btn-lg px-4 rounded-3">

                {{ __('ui.cancel') }}

            </a>

            <button
                type="submit"
                class="btn btn-primary btn-lg px-5 rounded-3"
                wire:loading.attr="disabled">

                <span wire:loading.remove wire:target="store">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ __('ui.save_purchase') }}
                </span>

                <span wire:loading wire:target="store">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    {{ __('ui.saving') }}
                </span>

            </button>

        </div>

    </form>

</div>

