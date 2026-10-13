<div class="container mx-auto w-full max-w-7xl px-3 py-4 sm:px-5 md:px-6 space-y-5 sm:space-y-6">

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

    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 rounded-xl bg-base-100 p-3 sm:p-4 shadow-sm">

        <img src="{{ $imageUrl }}"

             alt="{{ $customer->customer_name }}"

             class="h-16 w-16 sm:h-20 sm:w-20 shrink-0 rounded-full object-cover border border-base-300">

        <h2 class="text-lg sm:text-xl md:text-2xl font-bold break-words">

            Customer Name:

            <span class="text-primary">{{ $customer->customer_name }}</span>

        </h2>

    </div>

    {{-- Success Message --}}

    @if (session('success'))

        <div class="alert alert-success shadow-sm text-sm sm:text-base break-words">

            <div class="flex items-start gap-2">

                <svg xmlns="http\://www\.w3.org/2000/svg"

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

            <div class="w-full overflow-x-auto">

                <table class="table table-zebra w-full responsive-mobile-table">

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

                            <td data-label="তারিখ" class="whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}

                            </td>

                            <td data-label="পণ্য" class="max-w-48 whitespace-normal break-words">
                                {{ $product?->product_name ?? 'N/A' }}

                            </td>

                            <td data-label="মূল্য (৳)" class="whitespace-nowrap">
                                {{ number_format($totalPrice, 2) }} ৳

                            </td>

                            <td data-label="জমা (৳)" class="whitespace-nowrap">
                                {{ number_format($totalDeposit, 2) }} ৳

                            </td>

                            <td data-label="বাকি (৳)" class="whitespace-nowrap">
                                <span class="{{ $totalDue > 0 ? 'text-error font-bold' : 'text-success font-bold' }}">

                                    {{ number_format($totalDue, 2) }} ৳

                                </span>

                            </td>

                            {{-- Payment Input: Separate Form --}}

                            <td data-label="কিস্তি (৳)">
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

                            <td data-label="অ্যাকশন">
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

                    <tr>

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

            <div class="w-full overflow-x-auto">

                <table class="table table-zebra w-full responsive-mobile-table payment-history-table">

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

                            <td data-label="তারিখ" class="whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}

                            </td>

                            <td data-label="পণ্য" class="whitespace-normal break-words max-w-48">
                                {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}

                            </td>

                            <td data-label="জমা (৳)" class="text-success font-semibold whitespace-nowrap">
                                {{ number_format($payment->amount, 2) }}

                            </td>

                            @role('admin')

                                <td data-label="অ্যাকশন">
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

                        <tr>

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

<link href="https\://cdn.jsdelivr.net/npm/daisyui\@5" rel="stylesheet" type="text/css" />

<script src="https\://cdn.jsdelivr.net/npm/@tailwindcss/browser\@4"></script>


<style>
    /* Mobile-only presentation; desktop tables keep their normal layout. */
    @media (max-width: 639px) {
        .responsive-mobile-table,
        .responsive-mobile-table tbody,
        .responsive-mobile-table tr,
        .responsive-mobile-table td {
            display: block;
            width: 100%;
        }

        .responsive-mobile-table {
            min-width: 0 !important;
            border-collapse: separate;
            border-spacing: 0;
        }

        .responsive-mobile-table thead {
            display: none;
        }

        .responsive-mobile-table tbody tr:not(:has(td[colspan])) {
            display: block;
            margin: 0.75rem;
            width: calc(100% - 1.5rem);
            border: 1px solid hsl(var(--b3, 0 0% 80%));
            border-radius: 0.75rem;
            overflow: hidden;
            background: hsl(var(--b1, 0 0% 100%));
        }

        .responsive-mobile-table tbody tr:not(:has(td[colspan])) td {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            white-space: normal !important;
            overflow-wrap: anywhere;
            text-align: right;
            border-bottom: 1px solid hsl(var(--b3, 0 0% 80%));
        }

        .responsive-mobile-table tbody tr:not(:has(td[colspan])) td:last-child {
            border-bottom: 0;
        }

        .responsive-mobile-table tbody tr:not(:has(td[colspan])) td::before {
            content: attr(data-label);
            flex: 0 0 38%;
            max-width: 38%;
            font-weight: 700;
            text-align: left;
            overflow-wrap: anywhere;
        }

        .responsive-mobile-table tbody tr:not(:has(td[colspan])) td > * {
            min-width: 0;
            max-width: 62%;
        }

        .responsive-mobile-table input.input {
            width: 100%;
            max-width: 10rem;
        }

        .responsive-mobile-table .btn {
            min-height: 2.5rem;
        }

        .responsive-mobile-table td[colspan] {
            display: block;
            width: 100%;
            padding: 0.5rem !important;
        }

        .responsive-mobile-table td[colspan]::before {
            content: none !important;
        }

        .responsive-mobile-table .grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .responsive-mobile-table .min-w-24 {
            min-width: 0;
            width: 100%;
        }
    }
</style>
