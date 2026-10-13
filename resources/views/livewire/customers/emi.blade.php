<div class="customer-details-page container mx-auto w-full max-w-7xl px-3 py-4 sm:px-5 md:px-6 space-y-5 sm:space-y-6">



    {{-- Home Menu --}}

    <div>

        <a wire:navigate

           href="{{ route('customers.index') }}"

           class="btn btn-ghost btn-sm min-h-10">

            হোম মেনু&#x20;

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

    @endphp



    <div class="customer-header flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 rounded-xl bg-base-100 p-3 sm:p-4 shadow-sm">

        <img src="{{ $imageUrl }}"

             alt="{{ $customer->customer_name }}"

             class="h-16 w-16 sm:h-20 sm:w-20 shrink-0 rounded-full object-cover border border-base-300">



        <h2 class="customer-name text-lg sm:text-xl md:text-2xl font-bold break-words">

            Customer Name:

            <span class="text-primary">{{ $customer->customer_name }}</span>

        </h2>

    </div>



    {{-- Success Message --}}

    @if (session('success'))

        <div class="alert alert-success shadow-sm text-sm sm:text-base break-words">

            <div class="flex items-start gap-2">

                <svg xmlns="http://www.w3.org/2000/svg"

                     class="stroke-current h-6 w-6 shrink-0"

                     fill="none" viewBox="0 0 24 24">

                    <path stroke-linecap="round"

                          stroke-linejoin="round"

                          stroke-width="2"

                          d="M5 13l4 4L19 7"/>

                </svg>

                <span>{{ session('success') }}</span>

            </div>

        </div>

    @endif



    {{-- Purchase & EMI Summary --}}

    <div class="card bg-base-100 shadow-md overflow-hidden">

        <div class="card-body p-0">



            <div class="px-3 py-3 sm:px-4 border-b border-base-300">

                <h3 class="text-base sm:text-lg font-bold">পণ্য ও কিস্তির হিসাব</h3>

            </div>



            {{-- Responsive Table --}}

            <div class="table-wrap w-full overflow-x-auto">

                <table class="mobile-card-table purchase-table table table-zebra w-full min-w-[760px]">

                    <thead>

                        <tr class="bg-primary text-primary-content">

                            <th>তারিখ</th>

                            <th>পণ্য</th>

                            <th>মূল্য (৳)</th>

                            <th>জমা (৳)</th>

                            <th>বাকি (৳)</th>

                            <th>কিস্তি (৳)</th>

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



                            <td class="max-w-48 whitespace-normal break-words">

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



                            {{-- Payment Input: Separate Form --}}

                            <td>

                                @if (auth()->user()->hasRole('admin'))

                                    <input form="payment-form-{{ $purchase->id }}"

                                           type="number"

                                           name="payments[{{ $purchase->id }}]"

                                           class="input input-bordered input-sm w-28"

                                           value="0"

                                           min="0"

                                           max="{{ $totalDue }}"

                                           step="0.01"

                                           aria-label="কিস্তির পরিমাণ"

                                           {{ $totalDue <= 0 ? 'disabled' : '' }}>

                                @else

                                    <span class="text-base-content/60">—</span>

                                @endif

                            </td>



                            {{-- Actions --}}

                            <td>

                                @if (auth()->user()->hasRole('admin'))

                                    <div class="flex flex-col gap-2 min-w-24">



                                        <form id="payment-form-{{ $purchase->id }}"

                                              action="{{ route('installments.pay-multiple') }}"

                                              method="POST">

                                            @csrf

                                            <input type="hidden"

                                                   name="customer_id"

                                                   value="{{ $customer->id }}">



                                            <button type="submit"

                                                    class="btn btn-success btn-sm w-full"

                                                {{ $totalDue <= 0 ? 'disabled' : '' }}>

                                                Pay

                                            </button>

                                        </form>



                                        <form action="{{ route('purchases.destroy', $purchase->id) }}"

                                              method="POST"

                                              onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">

                                            @csrf

                                            @method('DELETE')



                                            <button type="submit"

                                                    class="btn btn-error btn-sm w-full">

                                                ডিলিট

                                            </button>

                                        </form>



                                    </div>

                                @else

                                    <span class="text-base-content/60">—</span>

                                @endif

                            </td>

                        </tr>

                    @endforeach



                    {{-- Totals Row --}}

                    <tr class="mobile-total-row">

                        <td colspan="7" class="p-2 sm:p-4">

                            <div class="bg-base-200 rounded-lg p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 font-semibold">



                                <div class="break-words">

                                    মোট মূল্য:

                                    <span class="font-bold whitespace-nowrap">

                                        {{ number_format($grandTotalPrice, 2) }} ৳

                                    </span>

                                </div>



                                <div class="break-words">

                                    মোট জমা:

                                    <span class="font-bold whitespace-nowrap">

                                        {{ number_format($grandTotalPaid + $grandTotalDown, 2) }} ৳

                                    </span>

                                </div>



                                <div class="{{ $grandTotalDue > 0 ? 'text-error' : 'text-success' }} font-bold break-words">

                                    মোট বাকি:

                                    <span class="whitespace-nowrap">

                                        {{ number_format($grandTotalDue, 2) }} ৳

                                    </span>

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

    <div class="card bg-base-100 shadow-md overflow-hidden">

        <div class="card-body p-0">



            <div class="px-3 py-3 sm:px-4 bg-primary text-primary-content font-bold text-base sm:text-lg">

                Payment History

            </div>



            <div class="table-wrap w-full overflow-x-auto">

                <table class="mobile-card-table payment-table table table-zebra w-full min-w-[460px]">

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



                            <td class="whitespace-normal break-words max-w-48">

                                {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}

                            </td>



                            <td class="text-success font-semibold whitespace-nowrap">

                                {{ number_format($payment->amount, 2) }}

                            </td>



                            @role('admin')

                                <td>

                                    <div class="flex flex-col gap-2 min-w-24">



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

                        <tr class="mobile-empty-row">

                            <td colspan="{{ auth()->user()->hasRole('admin') ? 4 : 3 }}"

                                class="text-center text-base-content/60 italic py-6">

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



{{-- Keep these only if they are not already loaded in your main layout --}}

<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


<style>
/* Responsive styling only; all Blade/PHP logic and form actions stay unchanged. */
.customer-details-page,
.customer-details-page * {
    box-sizing: border-box;
}
.customer-details-page {
    width: 100%;
    max-width: 1280px;
    margin-left: auto;
    margin-right: auto;
    min-width: 0;
}
.customer-details-page img {
    max-width: 100%;
}
.customer-details-page .table-wrap {
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.customer-details-page input[type="number"] {
    max-width: 100%;
}
@media (max-width: 640px) {
    .customer-details-page {
        padding-left: 10px !important;
        padding-right: 10px !important;
        padding-top: 12px !important;
    }
    .customer-details-page .customer-header {
        align-items: flex-start;
    }
    .customer-details-page .customer-name {
        width: 100%;
        min-width: 0;
        overflow-wrap: anywhere;
    }
    .customer-details-page .table-wrap {
        overflow: visible;
    }
    .customer-details-page table.mobile-card-table,
    .customer-details-page table.mobile-card-table tbody {
        display: block;
        width: 100% !important;
        min-width: 0 !important;
    }
    .customer-details-page table.mobile-card-table thead {
        display: none;
    }
    .customer-details-page table.mobile-card-table tbody > tr:not(.mobile-total-row) {
        display: block;
        width: 100%;
        margin: 0 0 12px;
        padding: 8px 10px;
        border: 1px solid rgba(127,127,127,.25);
        border-radius: 10px;
        background: var(--color-base-100, #fff);
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }
    .customer-details-page table.mobile-card-table tbody > tr:not(.mobile-total-row) > td {
        display: grid;
        grid-template-columns: minmax(95px, 40%) minmax(0, 1fr);
        align-items: start;
        gap: 8px;
        width: 100%;
        min-width: 0;
        padding: 8px 2px;
        border: 0;
        border-bottom: 1px solid rgba(127,127,127,.16);
        white-space: normal !important;
        overflow-wrap: anywhere;
        text-align: left;
    }
    .customer-details-page table.mobile-card-table tbody > tr:not(.mobile-total-row) > td:last-child {
        border-bottom: 0;
    }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(1)::before { content: "তারিখ"; }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(2)::before { content: "পণ্য"; }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(3)::before { content: "মূল্য (৳)"; }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(4)::before { content: "জমা (৳)"; }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(5)::before { content: "বাকি (৳)"; }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(6)::before { content: "কিস্তি (৳)"; }
    .customer-details-page table.purchase-table tbody > tr:not(.mobile-total-row) > td:nth-child(7)::before { content: "অ্যাকশন"; }

    .customer-details-page table.payment-table tbody > tr:not(.mobile-empty-row) > td:nth-child(1)::before { content: "তারিখ"; }
    .customer-details-page table.payment-table tbody > tr:not(.mobile-empty-row) > td:nth-child(2)::before { content: "পণ্য"; }
    .customer-details-page table.payment-table tbody > tr:not(.mobile-empty-row) > td:nth-child(3)::before { content: "জমা (৳)"; }
    .customer-details-page table.payment-table tbody > tr:not(.mobile-empty-row) > td:nth-child(4)::before { content: "অ্যাকশন"; }

    .customer-details-page table.mobile-card-table tbody > tr.mobile-empty-row {
        display: block;
        width: 100%;
        margin: 0;
        padding: 0;
        border: 0;
        box-shadow: none;
    }
    .customer-details-page table.mobile-card-table tbody > tr.mobile-empty-row > td {
        display: block;
        width: 100%;
        text-align: center;
    }
    .customer-details-page table.mobile-card-table tbody > tr.mobile-empty-row > td::before {
        content: none !important;
    }
    .customer-details-page table.mobile-card-table td::before {
        font-weight: 700;
        opacity: .8;
        min-width: 0;
        overflow-wrap: anywhere;
    }
    .customer-details-page table.mobile-card-table .input[type="number"] {
        width: 100%;
        min-width: 0;
    }
    .customer-details-page table.mobile-card-table form {
        width: 100%;
        min-width: 0;
    }
    .customer-details-page table.mobile-card-table .btn {
        min-height: 38px;
        width: 100%;
        white-space: normal;
    }
    .customer-details-page table.mobile-card-table .mobile-total-row {
        display: block;
        width: 100%;
        margin: 0;
        padding: 0;
    }
    .customer-details-page table.mobile-card-table .mobile-total-row > td {
        display: block;
        width: 100%;
        min-width: 0;
        padding: 8px 0;
        border: 0;
    }
    .customer-details-page table.mobile-card-table .mobile-total-row > td > div {
        width: 100%;
        min-width: 0;
        grid-template-columns: minmax(0, 1fr);
    }
}
</style>
