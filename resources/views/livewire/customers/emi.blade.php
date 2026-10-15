
<style>
    /* Customer page custom responsive CSS */
    .customer-page {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        padding: 1rem;
        overflow-x: clip;
    }

    .customer-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 0;
    }

    .customer-avatar {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        flex-shrink: 0;
    }

    .customer-name {
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: normal;
        font-size: 1.25rem;
        line-height: 1.5;
        font-weight: 700;
        margin: 0;
    }

    .customer-card {
        width: 100%;
        min-width: 0;
        border: 1px solid #dee2e6;
        border-radius: 0.65rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .customer-card-body {
        width: 100%;
        min-width: 0;
    }

    .customer-table-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        max-height: 400px;
        -webkit-overflow-scrolling: touch;
    }

    .customer-table {
        width: 100%;
        min-width: 650px;
        margin-bottom: 0;
        vertical-align: middle;
    }

    .customer-table th,
    .customer-table td {
        padding: 0.75rem;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .customer-table thead th {
        white-space: nowrap;
    }

    .customer-product-name {
        min-width: 120px;
        overflow-wrap: anywhere;
    }

    .customer-payment-input {
        width: 115px;
        max-width: 100%;
    }

    .customer-payment-form {
        min-width: 100px;
    }

    .customer-totals {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-around;
        gap: 1rem;
        padding: 1rem;
        background: #f1f3f5;
        font-weight: 600;
    }

    .customer-total-item {
        max-width: 100%;
        overflow-wrap: anywhere;
    }

    .customer-section-title {
        padding: 0.9rem 1rem;
        background: #0d6efd;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .customer-history-table {
        min-width: 420px;
    }

    .customer-action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 75px;
    }

    .customer-action-buttons .btn {
        white-space: nowrap;
    }

    .customer-alert {
        overflow-wrap: anywhere;
    }

    @media (max-width: 576px) {
        .customer-page {
            padding: 0.65rem;
        }

        .customer-header {
            align-items: flex-start;
            gap: 0.75rem;
        }

        .customer-avatar {
            width: 48px;
            height: 48px;
        }

        .customer-name {
            font-size: 1.05rem;
        }

        .customer-table th,
        .customer-table td {
            padding: 0.6rem;
            font-size: 0.9rem;
        }

        .customer-section-title {
            font-size: 1rem;
        }

        .customer-totals {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.85rem;
        }

        .customer-payment-input {
            width: 100px;
        }
    }

    @media (min-width: 768px) {
        .customer-page {
            padding: 1.25rem;
        }
    }

    @media (min-width: 1200px) {
        .customer-page {
            padding: 1.5rem;
        }
    }
</style>

<div class="container-fluid customer-page">

    {{-- Home Menu --}}
    <div class="mb-3">
        <a wire:navigate
           href="{{ route('customers.index') }}"
           class="btn btn-outline-secondary btn-sm">
            হোম মেনু
        </a>
    </div>

    {{-- Customer Header --}}
    @php
        $imagePath = $customer->customer_image;
        $imageUrl = asset('image/profile.png');

        if ($imagePath && file_exists(public_path($imagePath))) {
            $imageUrl = asset($imagePath);
        } elseif (
            $imagePath &&
            \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)
        ) {
            $imageUrl = asset('storage/' . $imagePath);
        }

        $customerName = trim($customer->customer_name ?? '');

        $nameWords = preg_split(
            '/\s+/u',
            $customerName,
            -1,
            PREG_SPLIT_NO_EMPTY
        ) ?: [];

        $shortCustomerName = count($nameWords) > 10
            ? implode(' ', array_slice($nameWords, 0, 10)) . '...'
            : $customerName;
    @endphp

    <div class="customer-header mb-4">
        <img src="{{ $imageUrl }}"
             alt="{{ $customerName }}"
             class="customer-avatar">

        <h4 class="customer-name">
            <span>Name:</span>
            <span class="text-primary">{{ $shortCustomerName }}</span>
        </h4>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show customer-alert"
             role="alert">
            <div class="d-flex align-items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="22"
                     height="22"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24"
                     aria-hidden="true"
                     class="flex-shrink-0">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>

                <span>{{ session('success') }}</span>
            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Purchase & EMI Summary --}}
    <div class="customer-card mb-4">
        <div class="customer-card-body">

            <div class="customer-table-wrapper">
                <table class="table table-striped table-hover table-bordered customer-table">
                    <thead class="table-primary">
                        <tr>
                            <th>তারিখ</th>
                            <th>পণ্য</th>
                            <th>মূল্য</th>
                            <th>জমা</th>
                            <th>বাকি</th>
                            <th>কিস্তি</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $grandTotalPrice = 0;
                            $grandTotalPaid = 0;
                            $grandTotalDue = 0;
                            $grandTotalDown = 0;
                        @endphp

                        @foreach ($customer->purchases as $purchase)
                            @php
                                $product = $purchase->product;

                                $totalPrice = round(
                                    (float) $purchase->net_price,
                                    2
                                );

                                $down = round(
                                    (float) $purchase->down_price,
                                    2
                                );

                                $totalPaid = round(
                                    (float) $purchase->installments->sum('paid_amount'),
                                    2
                                );

                                $totalDue = max(
                                    round($totalPrice - $down - $totalPaid, 2),
                                    0
                                );

                                $totalDeposit = round(
                                    $totalPaid + $down,
                                    2
                                );

                                $grandTotalPrice += $totalPrice;
                                $grandTotalPaid += $totalPaid;
                                $grandTotalDown += $down;
                                $grandTotalDue += $totalDue;
                            @endphp

                            <tr>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}
                                </td>

                                <td class="customer-product-name">
                                    {{ $product?->product_name ?? 'N/A' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ number_format($totalPrice, 2) }} ৳
                                </td>

                                <td class="text-nowrap">
                                    {{ number_format($totalDeposit, 2) }} ৳
                                </td>

                                <td class="text-nowrap">
                                    <span class="{{ $totalDue > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                        {{ number_format($totalDue, 2) }} ৳
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('installments.pay-multiple') }}"
                                          method="POST"
                                          class="customer-payment-form">
                                        @csrf

                                        <input type="hidden"
                                               name="customer_id"
                                               value="{{ $customer->id }}">

                                        <input type="number"
                                               name="payments[{{ $purchase->id }}]"
                                               class="form-control form-control-sm customer-payment-input"
                                               value="0"
                                               min="0"
                                               max="{{ $totalDue }}"
                                               step="0.01"
                                               {{ $totalDue <= 0 ? 'disabled' : '' }}>

                                        @if (auth()->user()->hasRole('admin'))
                                            <button type="submit"
                                                    class="btn btn-success btn-sm mt-2"
                                                    {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                                Pay
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                <td>
                                    @if (auth()->user()->hasRole('admin'))
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                ডিলিট
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        {{-- Totals Row --}}
                        <tr>
                            <td colspan="7" class="p-0">
                                <div class="customer-totals">
                                    <div class="customer-total-item">
                                        মোট মূল্য:
                                        <span class="fw-bold">
                                            {{ number_format($grandTotalPrice, 2) }} ৳
                                        </span>
                                    </div>

                                    <div class="customer-total-item">
                                        মোট জমা:
                                        <span class="fw-bold">
                                            {{ number_format($grandTotalPaid + $grandTotalDown, 2) }} ৳
                                        </span>
                                    </div>

                                    <div class="customer-total-item {{ $grandTotalDue > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                        মোট বাকি:
                                        {{ number_format($grandTotalDue, 2) }} ৳
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Payment History --}}
    <div class="customer-card mb-4">
        <div class="customer-section-title">
            Payment History
        </div>

        <div class="customer-card-body">
            <div class="customer-table-wrapper">
                <table class="table table-striped table-hover table-bordered customer-table customer-history-table">
                    <thead class="table-light">
                        <tr>
                            <th>তারিখ</th>
                            <th>পণ্য</th>
                            <th>জমা (৳)</th>

                            @role('admin')
                                <th>অ্যাকশন</th>
                            @endrole
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($paymentHistory as $payment)
                            <tr>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}
                                </td>

                                <td class="customer-product-name">
                                    {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}
                                </td>

                                <td class="text-success fw-semibold text-nowrap">
                                    {{ number_format($payment->amount, 2) }}
                                </td>

                                @role('admin')
                                    <td>
                                        <div class="customer-action-buttons">
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                               class="btn btn-warning btn-sm">
                                                এডিট
                                            </a>

                                            <form action="{{ route('payments.destroy', $payment->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই পেমেন্ট মুছতে চান?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm w-100">
                                                    ডিলিট
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? 4 : 3 }}"
                                    class="text-center text-secondary py-4">
                                    কোন পেমেন্ট পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
