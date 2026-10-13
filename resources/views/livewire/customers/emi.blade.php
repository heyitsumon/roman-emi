```blade
<div class="container-fluid container-lg py-3 py-md-4">

    {{-- Home Menu --}}
    <div class="mb-3">
        <a wire:navigate href="{{ route('customers.index') }}"
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
        } elseif ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
            $imageUrl = asset('storage/' . $imagePath);
        }
    @endphp

    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-center gap-3 mb-4">
        <img src="{{ $imageUrl }}"
             alt="{{ $customer->customer_name }}"
             class="rounded-circle border shadow-sm flex-shrink-0"
             style="width: 64px; height: 64px; object-fit: cover;">

        <h2 class="h5 h4-md fw-bold text-center text-sm-start mb-0 text-break">
            Customer Name:
            <span class="text-primary">{{ $customer->customer_name }}</span>
        </h2>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4"
             role="alert">
            <div class="d-flex align-items-center gap-2 text-break">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2"
                     class="flex-shrink-0">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>

                <span>{{ session('success') }}</span>
            </div>

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Purchase & EMI Summary --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-2 p-sm-3 p-md-4">

            <h5 class="card-title fw-bold mb-3">
                Purchase &amp; EMI Summary
            </h5>

            {{-- Responsive Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-nowrap">তারিখ</th>
                            <th>পণ্য</th>
                            <th class="text-nowrap">মূল্য</th>
                            <th class="text-nowrap">জমা</th>
                            <th class="text-nowrap">বাকি</th>
                            <th class="text-nowrap">কিস্তি</th>
                            <th class="text-nowrap">অ্যাকশন</th>
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

                                <td class="text-break">
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

                                {{-- Payment Form --}}
                                <td>
                                    <form action="{{ route('installments.pay-multiple') }}"
                                          method="POST"
                                          class="d-flex flex-column gap-2"
                                          style="min-width: 110px;">
                                        @csrf

                                        <input type="hidden"
                                               name="customer_id"
                                               value="{{ $customer->id }}">

                                        <input type="number"
                                               name="payments[{{ $purchase->id }}]"
                                               class="form-control form-control-sm"
                                               value="0"
                                               min="0"
                                               max="{{ $totalDue }}"
                                               step="0.01"
                                               {{ $totalDue <= 0 ? 'disabled' : '' }}>

                                        @if (auth()->user()->hasRole('admin'))
                                            <button type="submit"
                                                    class="btn btn-success btn-sm w-100"
                                                    {{ $totalDue <= 0 ? 'disabled' : '' }}>
                                                Pay
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                {{-- Purchase Delete --}}
                                <td>
                                    @if (auth()->user()->hasRole('admin'))
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                              method="POST"
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
                            <td colspan="7" class="p-2">
                                <div class="bg-light border rounded p-3">
                                    <div class="row g-3 fw-semibold">

                                        <div class="col-12 col-md-4">
                                            মোট মূল্য:
                                            <span class="fw-bold text-dark">
                                                {{ number_format($grandTotalPrice, 2) }} ৳
                                            </span>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            মোট জমা:
                                            <span class="fw-bold text-dark">
                                                {{ number_format($grandTotalPaid + $grandTotalDown, 2) }} ৳
                                            </span>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <span class="{{ $grandTotalDue > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                মোট বাকি:
                                                {{ number_format($grandTotalDue, 2) }} ৳
                                            </span>
                                        </div>

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
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-primary text-white fw-bold py-3">
            Payment History
        </div>

        <div class="card-body p-2 p-sm-3 p-md-4">

            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">তারিখ</th>
                            <th>পণ্য</th>
                            <th class="text-nowrap">জমা (৳)</th>

                            @role('admin')
                                <th class="text-nowrap">অ্যাকশন</th>
                            @endrole
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($paymentHistory as $payment)
                            <tr>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}
                                </td>

                                <td class="text-break">
                                    {{ $payment->installment->purchase->product->product_name ?? 'N/A' }}
                                </td>

                                <td class="text-success fw-semibold text-nowrap">
                                    {{ number_format($payment->amount, 2) }}
                                </td>

                                @role('admin')
                                    <td>
                                        <div class="d-flex flex-column flex-sm-row gap-2">

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
                                <td colspan="@role('admin')4 @else 3 @endrole"
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

</div>

<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>>
