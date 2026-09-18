<div class="container-fluid py-4">

    @role('admin')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ __('ui.admin_dashboard') }}</h2>
            <small class="text-muted">Operations overview</small>
        </div>

        <div class="text-muted small fw-semibold">
            {{ now()->format('d M Y') }}
        </div>
    </div>

    {{-- Selected Period --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Selected period</h5>
                    <small class="text-muted">
                        Sales, profit, paid, and due for the selected period
                    </small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label for="dashboard-period" class="small fw-semibold text-muted">
                        Period
                    </label>

                    <select id="dashboard-period"
                            wire:model.live="period"
                            class="form-select">
                        <option value="today">Today</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>

            <div class="row g-3">

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-semibold">
                                {{ ucfirst($period) }} Sales
                            </div>
                            <div class="fs-4 fw-bold text-primary mt-2">
                                ৳ {{ number_format($selectedPeriod['sales'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 bg-dark text-white h-100">
                        <div class="card-body">
                            <div class="small text-white-50 fw-semibold">
                                {{ ucfirst($period) }} Profit
                            </div>
                            <div class="fs-4 fw-bold mt-2">
                                ৳ {{ number_format($selectedPeriod['profit'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-semibold">
                                {{ ucfirst($period) }} Paid
                            </div>
                            <div class="fs-4 fw-bold text-success mt-2">
                                ৳ {{ number_format($selectedPeriod['paid'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-semibold">
                                {{ ucfirst($period) }} Due
                            </div>
                            <div class="fs-4 fw-bold text-danger mt-2">
                                ৳ {{ number_format($selectedPeriod['due'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Business Overview --}}
    <div class="row g-3 mb-4">

        @foreach ([
            ['label' => __('ui.total_customers'), 'value' => $totalCustomers],
            ['label' => __('ui.total_purchases'), 'value' => $totalPurchases],
            ['label' => __('ui.total_locations'), 'value' => $totalLocations],
            ['label' => 'Total Products', 'value' => $totalProducts],
        ] as $metric)

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">
                            {{ $metric['label'] }}
                        </div>

                        <div class="fs-3 fw-bold mt-2">
                            {{ number_format($metric['value']) }}
                        </div>

                        <small class="text-muted">
                            Active records
                        </small>
                    </div>
                </div>
            </div>

        @endforeach

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="text-muted small fw-semibold">
                        Total Sales
                    </div>

                    <div class="fs-3 fw-bold text-primary mt-2">
                        ৳ {{ number_format($totalSales, 2) }}
                    </div>

                    <small class="text-muted">
                        Customer total price
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white h-100">
                <div class="card-body">
                    <div class="small text-white-50 fw-semibold">
                        Total Profit
                    </div>

                    <div class="fs-3 fw-bold mt-2">
                        ৳ {{ number_format($totalProfit, 2) }}
                    </div>

                    <small class="text-white-50">
                        Customer total minus shop cost
                    </small>
                </div>
            </div>
        </div>

    </div>

    {{-- Cash Position --}}
    <div class="mb-4">

        <h5 class="fw-bold mb-3">Cash position</h5>

        <div class="row g-3">

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">Down Payments</div>
                        <div class="fs-4 fw-bold mt-2">
                            ৳ {{ number_format($totalDown, 2) }}
                        </div>
                        <small class="text-muted">Collected upfront</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">Total Paid</div>
                        <div class="fs-4 fw-bold text-success mt-2">
                            ৳ {{ number_format($totalPaid, 2) }}
                        </div>
                        <small class="text-muted">Including down payments</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">Total Due</div>
                        <div class="fs-4 fw-bold text-danger mt-2">
                            ৳ {{ number_format($totalDue, 2) }}
                        </div>
                        <small class="text-muted">Remaining from net price</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">Total Net / Cost</div>
                        <div class="fs-4 fw-bold text-warning mt-2">
                            ৳ {{ number_format($totalNet, 2) }}
                        </div>
                        <small class="text-muted">Recorded net value</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Period Financials --}}
    <div class="mb-4">

        <h5 class="fw-bold mb-3">Period financials</h5>

        <div class="row g-3">

            @foreach ([
                ['label' => 'Today Sales', 'value' => $today['sales'], 'color' => 'primary'],
                ['label' => 'Today Profit', 'value' => $today['profit'], 'color' => 'success'],
                ['label' => 'Today Due', 'value' => $today['due'], 'color' => 'danger']
            ] as $metric)

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-semibold">
                                {{ $metric['label'] }}
                            </div>

                            <div class="fs-3 fw-bold text-{{ $metric['color'] }} mt-2">
                                ৳ {{ number_format($metric['value'], 2) }}
                            </div>

                            <small class="text-muted">Today</small>
                        </div>
                    </div>
                </div>

            @endforeach

            @foreach ([
                ['label' => 'Month Sales', 'value' => $month['sales'], 'color' => 'primary'],
                ['label' => 'Month Profit', 'value' => $month['profit'], 'color' => 'success'],
                ['label' => 'Month Due', 'value' => $month['due'], 'color' => 'danger']
            ] as $metric)

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-semibold">
                                {{ $metric['label'] }}
                            </div>

                            <div class="fs-3 fw-bold text-{{ $metric['color'] }} mt-2">
                                ৳ {{ number_format($metric['value'], 2) }}
                            </div>

                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>

            @endforeach

            @foreach ([
                ['label' => 'Year Sales', 'value' => $year['sales'], 'color' => 'primary'],
                ['label' => 'Year Profit', 'value' => $year['profit'], 'color' => 'success'],
                ['label' => 'Year Due', 'value' => $year['due'], 'color' => 'danger']
            ] as $metric)

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-semibold">
                                {{ $metric['label'] }}
                            </div>

                            <div class="fs-3 fw-bold text-{{ $metric['color'] }} mt-2">
                                ৳ {{ number_format($metric['value'], 2) }}
                            </div>

                            <small class="text-muted">This year</small>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

    {{-- Yearly Performance --}}
    <div class="mb-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">

            <h5 class="fw-bold mb-0">Yearly performance</h5>

            <div class="d-flex align-items-center gap-2">
                <label for="dashboard-year" class="small fw-semibold text-muted">
                    Year
                </label>

                <select id="dashboard-year"
                        wire:model.live="selectedYear"
                        class="form-select">
                    @foreach ($annualData as $annual)
                        <option value="{{ $annual['year'] }}">
                            {{ $annual['year'] }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        {{-- Selected Annual --}}
        <div class="row g-3 mb-3">

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">
                            {{ $selectedAnnual['year'] }} Sales
                        </div>

                        <div class="fs-4 fw-bold text-primary mt-2">
                            ৳ {{ number_format($selectedAnnual['sales'], 2) }}
                        </div>

                        <small class="text-muted">
                            {{ number_format($selectedAnnual['purchases']) }} sales
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">
                            {{ $selectedAnnual['year'] }} Profit
                        </div>

                        <div class="fs-4 fw-bold text-success mt-2">
                            ৳ {{ number_format($selectedAnnual['profit'], 2) }}
                        </div>

                        <small class="text-muted">Annual profit</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">
                            {{ $selectedAnnual['year'] }} Paid
                        </div>

                        <div class="fs-4 fw-bold text-success mt-2">
                            ৳ {{ number_format($selectedAnnual['paid'], 2) }}
                        </div>

                        <small class="text-muted">Collected payments</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-semibold">
                            {{ $selectedAnnual['year'] }} Due
                        </div>

                        <div class="fs-4 fw-bold text-danger mt-2">
                            ৳ {{ number_format($selectedAnnual['due'], 2) }}
                        </div>

                        <small class="text-muted">Remaining balance</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- Annual Data --}}
        <div class="row g-3">

            @foreach ($annualData as $annual)

                <div class="col-12 col-md-6 col-xl-3">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="fs-5">
                                    {{ $annual['year'] }}
                                </strong>

                                <small class="text-muted">
                                    {{ number_format($annual['purchases']) }} sales
                                </small>
                            </div>

                            <div class="d-flex justify-content-between border-top mt-3 pt-3">
                                <span class="text-muted small">Sales</span>
                                <strong class="text-primary">
                                    ৳ {{ number_format($annual['sales'], 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                <span class="text-muted small">Profit</span>
                                <strong class="text-success">
                                    ৳ {{ number_format($annual['profit'], 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                <span class="text-muted small">Paid</span>
                                <strong>
                                    ৳ {{ number_format($annual['paid'], 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                <span class="text-muted small">Due</span>
                                <strong class="text-danger">
                                    ৳ {{ number_format($annual['due'], 2) }}
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

    {{-- Chart --}}
    <section class="card border-0 shadow-sm rounded-4" wire:ignore>

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Customers and purchases</h5>
                <small class="text-muted">Last six months</small>
            </div>

            <div style="height: 350px;">
                <canvas id="dashboardChart"></canvas>
            </div>

        </div>

    </section>

    @endrole

</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endassets

@script
<script>
    (() => {
        const chartLabels = @json($chartLabels);
        const customerData = @json($customerChartData);
        const purchaseData = @json($purchaseChartData);

        const renderDashboardChart = () => {
            const canvas = document.getElementById('dashboardChart');

            if (!canvas) return;

            if (typeof window.Chart === 'undefined') {
                window.setTimeout(renderDashboardChart, 100);
                return;
            }

            if (window.dashboardChart instanceof window.Chart) {
                window.dashboardChart.destroy();
            }

            const context = canvas.getContext('2d');

            const customerGradient = context.createLinearGradient(0, 0, 0, 300);

            customerGradient.addColorStop(
                0,
                'rgba(56, 103, 214, .24)'
            );

            customerGradient.addColorStop(
                1,
                'rgba(56, 103, 214, 0)'
            );

            const purchaseGradient = context.createLinearGradient(0, 0, 0, 300);

            purchaseGradient.addColorStop(
                0,
                'rgba(21, 154, 140, .2)'
            );

            purchaseGradient.addColorStop(
                1,
                'rgba(21, 154, 140, 0)'
            );

            window.dashboardChart = new window.Chart(context, {
                type: 'line',

                data: {
                    labels: chartLabels,

                    datasets: [
                        {
                            label: 'Customers',
                            data: customerData,

                            borderColor: '#3867d6',
                            backgroundColor: customerGradient,

                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#3867d6',
                            pointBorderWidth: 2,

                            pointRadius: 4,
                            pointHoverRadius: 7,

                            borderWidth: 3,
                            fill: true,
                            tension: .4,
                        },

                        {
                            label: 'Purchases',
                            data: purchaseData,

                            borderColor: '#159a8c',
                            backgroundColor: purchaseGradient,

                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#159a8c',
                            pointBorderWidth: 2,

                            pointRadius: 4,
                            pointHoverRadius: 7,

                            borderWidth: 3,
                            fill: true,
                            tension: .4,
                        },
                    ],
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    interaction: {
                        mode: 'index',
                        intersect: false
                    },

                    animation: {
                        duration: 1400,
                        easing: 'easeOutQuart'
                    },

                    plugins: {
                        legend: {
                            position: 'bottom',

                            labels: {
                                usePointStyle: true,
                                padding: 22,
                                color: '#687386',

                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },

                        tooltip: {
                            backgroundColor: '#172033',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,

                            border: {
                                display: false
                            },

                            grid: {
                                color: '#edf0f5'
                            },

                            ticks: {
                                color: '#8a94a6',
                                precision: 0
                            }
                        },

                        x: {
                            border: {
                                display: false
                            },

                            grid: {
                                display: false
                            },

                            ticks: {
                                color: '#8a94a6'
                            }
                        }
                    }
                }
            });
        };

        renderDashboardChart();

        document.addEventListener(
            'livewire:navigated',
            renderDashboardChart,
            { once: true }
        );
    })();
</script>
@endscript