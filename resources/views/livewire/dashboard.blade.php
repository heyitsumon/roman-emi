<div class="container-fluid py-4">
    @can('dashboard-view')
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-1">{{ __('ui.admin_dashboard') }}</h2>
                <p class="text-muted mb-0">Sales and business overview</p>
            </div>
            <div class="text-muted small fw-semibold">{{ now()->format('d M Y') }}</div>
        </div>

        <div class="row g-3 mb-4">
            @foreach ([
                ['title' => 'Today', 'data' => $today],
                ['title' => 'This Month', 'data' => $month],
                ['title' => 'This Year', 'data' => $year],
            ] as $periodCard)
                <div class="col-12 col-xl-4">
                    <section class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">{{ $periodCard['title'] }}</h5>
                            <div class="row g-3">
                                @foreach ([
                                    ['label' => 'Sales', 'value' => $periodCard['data']['sales'], 'color' => 'primary'],
                                    ['label' => 'Profit', 'value' => $periodCard['data']['profit'], 'color' => 'success'],
                                    ['label' => 'Paid', 'value' => $periodCard['data']['paid'], 'color' => 'info'],
                                    ['label' => 'Due', 'value' => $periodCard['data']['due'], 'color' => 'danger'],
                                ] as $metric)
                                    <div class="col-6">
                                        <div class="h-100 rounded-3 bg-light p-3">
                                            <div class="small text-muted fw-semibold">{{ $metric['label'] }}</div>
                                            <div class="fs-5 fw-bold text-{{ $metric['color'] }} mt-1">
                                                ৳ {{ number_format($metric['value'], 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>

        <section class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Business totals</h5>
                <div class="row g-3">
                    @foreach ([
                        ['label' => __('ui.total_customers'), 'value' => $totalCustomers, 'type' => 'count', 'color' => 'primary'],
                        ['label' => __('ui.total_locations'), 'value' => $totalLocations, 'type' => 'count', 'color' => 'info'],
                        ['label' => 'Total Products', 'value' => $totalProducts, 'type' => 'count', 'color' => 'warning'],
                        ['label' => 'Total Sales', 'value' => $totalSales, 'type' => 'money', 'color' => 'primary'],
                        ['label' => 'Total Profit', 'value' => $totalProfit, 'type' => 'money', 'color' => 'success'],
                        ['label' => 'Total Paid', 'value' => $totalPaid, 'type' => 'money', 'color' => 'info'],
                        ['label' => 'Total Due', 'value' => $totalDue, 'type' => 'money', 'color' => 'danger'],
                    ] as $metric)
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="h-100 rounded-3 bg-light p-3">
                                <div class="small text-muted fw-semibold">{{ $metric['label'] }}</div>
                                <div class="fs-4 fw-bold text-{{ $metric['color'] }} mt-2">
                                    @if ($metric['type'] === 'money')
                                        ৳ {{ number_format($metric['value'], 2) }}
                                    @else
                                        {{ number_format($metric['value']) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endcan
</div>
