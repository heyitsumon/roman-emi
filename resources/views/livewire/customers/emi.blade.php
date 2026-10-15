
<style>
    
/* Customer page: mobile font-size correction */
.customer-page,
.customer-page * {
    box-sizing: border-box;
}

.customer-page {
    width: 100%;
    max-width: 100%;
    font-size: 17px !important;
    line-height: 1.6;
}

.customer-page h1,
.customer-page h2,
.customer-page h3,
.customer-page h4,
.customer-page h5 {
    font-size: 22px !important;
    line-height: 1.5 !important;
}

.customer-page p,
.customer-page span,
.customer-page label,
.customer-page .alert {
    font-size: 17px;
    line-height: 1.6;
}

.customer-page .customer-table {
    font-size: 17px !important;
}

.customer-page .customer-table th,
.customer-page .customer-table td {
    font-size: 17px !important;
    line-height: 1.6 !important;
    padding: 12px !important;
}

.customer-page .btn,
.customer-page button {
    font-size: 16px !important;
    line-height: 1.5 !important;
    padding: 8px 12px;
}

.customer-page input,
.customer-page select,
.customer-page textarea {
    font-size: 16px !important;
    min-height: 42px;
}

.customer-page .customer-payment-input {
    width: 120px;
    max-width: 100%;
}

.customer-page .customer-table-wrapper {
    max-width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Mobile screens */
@media (max-width: 767.98px) {
    .customer-page {
        padding: 12px !important;
        font-size: 18px !important;
    }

    .customer-page h1,
    .customer-page h2,
    .customer-page h3,
    .customer-page h4,
    .customer-page h5,
    .customer-page .customer-name {
        font-size: 22px !important;
    }

    .customer-page p,
    .customer-page span,
    .customer-page label,
    .customer-page .alert {
        font-size: 18px;
    }

    .customer-page .customer-table th,
    .customer-page .customer-table td {
        font-size: 17px !important;
        padding: 12px !important;
    }

    .customer-page .btn,
    .customer-page button {
        font-size: 16px !important;
        min-height: 42px;
    }

    .customer-page input,
    .customer-page select,
    .customer-page textarea {
        font-size: 16px !important;
    }
}

    </style>
<div class="container-fluid customer-page">

    <div class="w-full">
        <a wire:navigate href="{{ route('customers.index') }}"
           class="btn btn-ghost btn-sm max-w-full">
            হোম মেনু
        </a>
    </div>

    {{-- Customer Header --}}
    @php
        $imagePath = $customer->customer_image;
        $imageUrl = asset('image/profile.png');

        if ($imagePath && file_exists(public_path($imagePath))) {
            $imageUrl = asset($imagePath);
        } elseif ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
            $imageUrl = asset('storage/' . $imagePath);
        }

        $customerName = trim($customer->customer_name ?? '');
        $nameWords = preg_split('/\s+/u', $customerName, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $shortCustomerName = count($nameWords) > 10
            ? implode(' ', array_slice($nameWords, 0, 10)) . '...'
            : $customerName;
    @endphp

    <div class="flex items-start gap-3 sm:items-center sm:gap-4 w-full min-w-0">
        <img src="{{ $imageUrl }}"
             alt="{{ $customerName }}"
             class="h-12 w-12 sm:h-16 sm:w-16 rounded-full object-cover border border-base-300 shrink-0">

        <h4 class="text-base sm:text-xl md:text-xl font-bold min-w-0 flex-1 leading-relaxed"
            style="overflow-wrap:anywhere; word-break:normal;">
            <span>Name:</span>
            <span class="text-primary">{{ $shortCustomerName }}</span>
        </h4>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success shadow-lg min-w-0 max-w-full">
            <div class="min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="stroke-current h-6 w-6 shrink-0"
                     fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="min-w-0" style="overflow-wrap:anywhere;">
                    {{ session('success') }}
                </span>
            </div>
        </div>
    @endif

    {{-- Purchase & EMI Summary --}}
    <div class="card bg-base-100 shadow-md w-full max-w-full min-w-0">
        <div class="card-body p-0 min-w-0">

            <div class="w-full max-w-full overflow-x-auto overflow-y-auto max-h-96"
                 style="-webkit-overflow-scrolling:touch;">
                <table class="table table-zebra w-full min-w-[650px]">
                    <thead>
                        <tr class="bg-primary text-primary-content">
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
                                <td class="whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}
                                </td>

                                <td class="min-w-0" style="overflow-wrap:anywhere;">
                                    {{ $product?->product_name ?? 'N/A' }}
                                </td>

                                <td class="whitespace-nowrap">
                                    {{ number_format($totalPrice, 2) }} ৳
                                </td>

                                <td class="whitespace-nowrap">
                                    {{ number_format($totalDeposit, 2) }} ৳
                                </td>

                                <td class="whitespace-nowrap">
                                    <span class="{{ $totalDue > 0 ? 'text-error font-bold' : 'text-success font-bold' }}">
                                        {{ number_format($totalDue, 2) }} ৳
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('installments.pay-multiple') }}"
                                          method="POST"
                                          class="min-w-0">
                                        @csrf
                                        <input type="hidden"
                                               name="customer_id"
                                               value="{{ $customer->id }}">

                                        <input type="number"
                                               name="payments[{{ $purchase->id }}]"
                                               class="input input-sm input-bordered w-24 max-w-full"
                                               value="0"
                                               min="0"
                                               max="{{ $totalDue }}"
                                               step="0.01"
                                            {{ $totalDue <= 0 ? 'disabled' : '' }}>

                                        @if (auth()->user()->hasRole('admin'))
                                            <button type="submit"
                                                    class="btn btn-success btn-sm mt-2 w-full"
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
                                              class="min-w-0"
                                              onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-error btn-sm w-full">
                                                ডিলিট
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        {{-- Totals Row --}}
                        <tr>
                            <td colspan="7" class="whitespace-normal">
                                <div class="bg-base-200 rounded-lg p-3 sm:p-4 flex flex-col md:flex-row justify-around items-start md:items-center gap-3 font-semibold w-full min-w-0">
                                    <div class="max-w-full" style="overflow-wrap:anywhere;">
                                        মোট মূল্য:
                                        <span class="font-bold">
                                            {{ number_format($grandTotalPrice, 2) }} ৳
                                        </span>
                                    </div>

                                    <div class="max-w-full" style="overflow-wrap:anywhere;">
                                        মোট জমা:
                                        <span class="font-bold">
                                            {{ number_format($grandTotalPaid + $grandTotalDown, 2) }} ৳
                                        </span>
                                    </div>

                                    <div class="{{ $grandTotalDue > 0 ? 'text-error font-bold' : 'text-success font-bold' }} max-w-full"
                                         style="overflow-wrap:anywhere;">
                                        মোট বাকি: {{ number_format($grandTotalDue, 2) }} ৳
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
    <div class="card bg-base-100 shadow-md w-full max-w-full min-w-0">
        <div class="card-body p-0 min-w-0">

            <div class="px-3 sm:px-4 py-3 bg-primary text-primary-content font-bold text-base sm:text-lg"
                 style="overflow-wrap:anywhere;">
                Payment History
            </div>

            <div class="w-full max-w-full overflow-x-auto overflow-y-auto max-h-96"
                 style="-webkit-overflow-scrolling:touch;">
                <table class="table table-zebra w-full min-w-[420px]">
                    <thead>
                        <tr class="bg-base-200">
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
                                <td class="whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}
                                </td>

                                <td style="overflow-wrap:anywhere;">
                                    {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}
                                </td>

                                <td class="text-success font-semibold whitespace-nowrap">
                                    {{ number_format($payment->amount, 2) }}
                                </td>

                                @role('admin')
                                    <td>
                                        <div class="flex flex-col gap-2 min-w-0">
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                               class="btn btn-warning btn-sm w-full">
                                                এডিট
                                            </a>

                                            <form action="{{ route('payments.destroy', $payment->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই পেমেন্ট মুছতে চান?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-error btn-sm w-full">
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
                                    class="text-center text-gray-500 italic py-4 whitespace-normal">
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

