
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Customer Details</title>

    {{-- Bootstrap 5.3.8 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        /* Customer page base */
        .customer-page,
        .customer-page * {
            box-sizing: border-box;
        }

        .customer-page {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            padding: 16px;
            font-size: 16px;
            line-height: 1.6;
            color: #212529;
            overflow-wrap: anywhere;
        }

        .customer-page h1,
        .customer-page h2,
        .customer-page h3,
        .customer-page h4,
        .customer-page h5 {
            font-size: 22px;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .customer-page p,
        .customer-page span,
        .customer-page label {
            line-height: 1.6;
        }

        .customer-page .customer-card {
            width: 100%;
            min-width: 0;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .customer-page .customer-card-header {
            padding: 14px 18px;
            background: #0d6efd;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
        }

        .customer-page .customer-profile {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            padding: 20px 0;
        }

        .customer-page .customer-avatar {
            width: 64px;
            height: 64px;
            min-width: 64px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }

        .customer-page .customer-name {
            min-width: 0;
            overflow-wrap: anywhere;
            font-size: 22px;
            font-weight: 700;
        }

        .customer-page .customer-table-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .customer-page .customer-table {
            width: 100%;
            margin-bottom: 0;
            font-size: 15px;
            vertical-align: middle;
        }

        .customer-page .customer-table th,
        .customer-page .customer-table td {
            padding: 12px;
            line-height: 1.6;
        }

        .customer-page .customer-table thead th {
            background: #eaf2ff;
            color: #212529;
            font-weight: 700;
            white-space: nowrap;
        }

        .customer-page .customer-table tbody tr:hover > * {
            background-color: #f8faff;
        }

        .customer-page .purchase-table {
            min-width: 760px;
        }

        .customer-page .history-table {
            min-width: 420px;
        }

        .customer-page .customer-payment-input {
            width: 120px;
            max-width: 100%;
            min-height: 42px;
        }

        .customer-page .form-control {
            font-size: 16px;
            min-height: 42px;
        }

        .customer-page .btn {
            font-size: 15px;
            line-height: 1.5;
            padding: 8px 12px;
            white-space: normal;
        }

        .customer-page .customer-action-form {
            min-width: 75px;
        }

        .customer-page .customer-total-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
            gap: 14px;
            width: 100%;
            padding: 16px;
            background: #f1f5f9;
            border-radius: 8px;
        }

        .customer-page .customer-total-item {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .customer-page .customer-alert {
            overflow-wrap: anywhere;
            word-break: normal;
        }

        .customer-page .customer-section {
            margin-top: 22px;
        }

        /* Tablet and mobile */
        @media (max-width: 767.98px) {
            .customer-page {
                padding: 12px !important;
                font-size: 16px;
            }

            .customer-page .customer-profile {
                align-items: flex-start;
                gap: 12px;
                padding: 16px 0;
            }

            .customer-page .customer-avatar {
                width: 52px;
                height: 52px;
                min-width: 52px;
            }

            .customer-page .customer-name {
                font-size: 20px;
            }

            .customer-page .customer-card-header {
                padding: 12px;
                font-size: 17px;
            }

            .customer-page .customer-table th,
            .customer-page .customer-table td {
                padding: 10px !important;
                font-size: 15px;
            }

            .customer-page .btn {
                min-height: 42px;
                font-size: 15px;
            }

            .customer-page .customer-total-box {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px;
                gap: 10px;
            }

            .customer-page .customer-payment-input {
                width: 100px;
            }
        }

        @media (max-width: 380px) {
            .customer-page {
                padding: 8px !important;
            }

            .customer-page .customer-avatar {
                width: 44px;
                height: 44px;
                min-width: 44px;
            }

            .customer-page .customer-name {
                font-size: 18px;
            }

            .customer-page .customer-table th,
            .customer-page .customer-table td {
                padding: 8px !important;
            }
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid customer-page">

    {{-- Home Menu --}}
    <div class="mb-2">
        <a wire:navigate
           href="{{ route('customers.index') }}"
           class="btn btn-outline-primary btn-sm">
            ← হোম মেনু
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
        $nameWords = preg_split('/\s+/u', $customerName, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $shortCustomerName = count($nameWords) > 10
            ? implode(' ', array_slice($nameWords, 0, 10)) . '...'
            : $customerName;
    @endphp

    <div class="customer-profile">
        <img src="{{ $imageUrl }}"
             alt="{{ $customerName }}"
             class="customer-avatar">

        <h4 class="customer-name">
            <span class="text-secondary">Name:</span>
            <span class="text-primary">{{ $shortCustomerName }}</span>
        </h4>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show customer-alert shadow-sm"
             role="alert">
            <strong>✓</strong>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Purchase & EMI Summary --}}
    <div class="customer-card customer-section">

        <div class="customer-card-header">
            Purchase &amp; EMI Summary
        </div>

        <div class="customer-table-wrapper">
            <table class="table table-striped table-hover customer-table purchase-table">
                <thead>
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
                            $totalPrice = round((float) $purchase->net_price, 2);
                            $down = round((float) $purchase->down_price, 2);
                            $totalPaid = round((float) $purchase->installments->sum('paid_amount'), 2);
                            $totalDue = max(round($totalPrice - $down - $totalPaid, 2), 0);
                            $totalDeposit = round($totalPaid + $down, 2);

                            $grandTotalPrice += $totalPrice;
                            $grandTotalPaid += $totalPaid;
                            $grandTotalDown += $down;
                            $grandTotalDue += $totalDue;
                        @endphp

                        <tr>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}
                            </td>

                            <td>
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
                                      class="customer-action-form">
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
                                                class="btn btn-success btn-sm mt-2 w-100"
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
                                          class="customer-action-form"
                                          onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm w-100">
                                            ডিলিট
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    {{-- Totals Row --}}
                    <tr>
                        <td colspan="7" class="p-3">
                            <div class="customer-total-box">

                                <div class="customer-total-item">
                                    মোট মূল্য:
                                    <strong>
                                        {{ number_format($grandTotalPrice, 2) }} ৳
                                    </strong>
                                </div>

                                <div class="customer-total-item">
                                    মোট জমা:
                                    <strong>
                                        {{ number_format($grandTotalPaid + $grandTotalDown, 2) }} ৳
                                    </strong>
                                </div>

                                <div class="customer-total-item {{ $grandTotalDue > 0 ? 'text-danger' : 'text-success' }}">
                                    মোট বাকি:
                                    <strong>
                                        {{ number_format($grandTotalDue, 2) }} ৳
                                    </strong>
                                </div>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="customer-card customer-section">

        <div class="customer-card-header">
            Payment History
        </div>

        <div class="customer-table-wrapper">
            <table class="table table-striped table-hover customer-table history-table">
                <thead>
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

                            <td>
                                {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}
                            </td>

                            <td class="text-success fw-semibold text-nowrap">
                                {{ number_format($payment->amount, 2) }}
                            </td>

                            @role('admin')
                                <td>
                                    <div class="d-flex flex-column gap-2">

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
                                class="text-center text-secondary fst-italic py-4">
                                কোন পেমেন্ট পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Bootstrap 5.3.8 JavaScript --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
