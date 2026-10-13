
<div class="w-full max-w-full min-w-0 overflow-x-hidden px-3 sm:px-4 lg:px-6 py-4 space-y-5 sm:space-y-6">

    {{-- Home Button --}}
    <div class="w-full">
        <a wire:navigate
           href="{{ route('customers.index') }}"
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
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center items-start gap-3 sm:gap-4 min-w-0">
        <img src="{{ $imageUrl }}"
             alt="{{ $customer->customer_name }}"
             class="h-14 w-14 sm:h-16 sm:w-16 rounded-full object-cover border border-base-300 shrink-0">

        <h2 class="text-lg sm:text-xl md:text-2xl font-bold min-w-0 max-w-full break-words">
            Customer Name:
            <span class="text-primary">{{ $customer->customer_name }}</span>
        </h2>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success shadow-lg w-full min-w-0">
            <div class="flex items-start gap-2 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="stroke-current h-6 w-6 shrink-0"
                     fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                <span class="break-words">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Purchase & EMI Summary --}}
    <div class="card bg-base-100 shadow-md w-full min-w-0">
        <div class="card-body p-0 min-w-0">
            <div class="w-full max-w-full overflow-x-auto overscroll-x-contain">
                <table class="table table-zebra w-full min-w-[680px]">
                    <thead>
                    <tr class="bg-primary text-primary-content">
                        <th>তারিখ</th>
                        <th>পণ্য</th>
                        <th>মূল্য bangladesh4</th>
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

        <td class="break-words min-w-[120px]">
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

        <td class="min-w-[130px]">
            @if (auth()->user()->hasRole('admin'))
                <form action="{{ route('installments.pay-multiple') }}"
                      method="POST"
                      class="flex flex-col gap-2 min-w-[110px]">
                    @csrf

                    <input type="hidden"
                           name="customer_id"
                           value="{{ $customer->id }}">

                    <input type="number"
                           name="payments[{{ $purchase->id }}]"
                           class="input input-bordered input-sm w-full"
                           value="0"
                           min="0"
                           max="{{ $totalDue }}"
                           step="0.01"
                           {{ $totalDue <= 0 ? 'disabled' : '' }}>

                    <button type="submit"
                            class="btn btn-success btn-sm w-full"
                            {{ $totalDue <= 0 ? 'disabled' : '' }}>
                        Pay
                    </button>
                </form>
            @else
                <span>—</span>
            @endif
        </td>

        <td class="min-w-[100px]">
            @if (auth()->user()->hasRole('admin'))
                <form action="{{ route('purchases.destroy', $purchase->id) }}"
                      method="POST"
                      onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-error btn-sm w-full">
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
                            <div class="bg-base-200 rounded-lg p-3 sm:p-4 flex flex-col sm:flex-row flex-wrap justify-around items-start sm:items-center gap-3 font-semibold">
                                <div class="break-words">
                                    মোট মূল্য:
                                    <span class="font-bold">
                                        {{ number_format($grandTotalPrice, 2) }} ৳
                                    </span>
                                </div>

                                <div class="break-words">
                                    মোট জমা:
                                    <span class="font-bold">
                                        {{ number_format($grandTotalPaid + $grandTotalDown, 2) }} ৳
                                    </span>
                                </div>

                                <div class="break-words {{ $grandTotalDue > 0 ? 'text-error font-bold' : 'text-success font-bold' }}">
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
    <div class="card bg-base-100 shadow-md w-full min-w-0">
        <div class="card-body p-0 min-w-0">

            <div class="px-3 sm:px-4 py-3 bg-primary text-primary-content font-bold text-base sm:text-lg rounded-t-lg">
                Payment History
            </div>

            <div class="w-full max-w-full overflow-x-auto overscroll-x-contain">
                <table class="table table-zebra w-full min-w-[400px]">
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

                            <td class="min-w-[120px] max-w-[220px] whitespace-normal break-words">
                                {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}
                            </td>

                            <td class="text-success font-semibold whitespace-nowrap">
                                {{ number_format($payment->amount, 2) }}
                            </td>

                            @role('admin')
                            <td class="min-w-[100px]">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('payments.edit', $payment->id) }}"
                                       class="btn btn-warning btn-sm w-full">
                                        এডিট
                                    </a>

                                    <form action="{{ route('payments.destroy', $payment->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই পেমেন্ট মুছতে চান?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-error btn-sm w-full">
                                            ডিলিট
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="@role('admin')4 @else 3 @endrole"
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

<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
