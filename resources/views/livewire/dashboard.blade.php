<div class="container-fluid py-4">
    @can('dashboard-view')
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">{{ __('ui.admin_dashboard') }}</h2>
                <p class="text-muted mb-0">Sales and business overview</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label for="dashboard-year" class="small fw-semibold text-muted mb-0">Year</label>
                    <select id="dashboard-year" wire:model.live="selectedYear" class="form-select">
                        @foreach ($annualData as $annual)
                            <option value="{{ $annual['year'] }}">{{ $annual['year'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-muted small fw-semibold">{{ now()->format('d M Y') }}</div>
            </div>
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

        <section class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Monthly financials</h5>
                    <p class="text-muted small mb-0">Sales, profit, paid, and due for {{ $selectedYear }}</p>
                </div>
                <div wire:ignore style="height: 360px;">
                    <canvas id="dashboardYearChart" aria-label="Monthly financial chart"></canvas>
                </div>
            </div>
        </section>
    @endcan
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endassets

@script
<script>
    (() => {
        const yearlyChartData = @json($yearlyChartData);
        let selectedYear = @js($selectedYear);

        const renderDashboardChart = () => {
            const canvas = document.getElementById('dashboardYearChart');
            const data = yearlyChartData[selectedYear];

            if (!canvas || !data) return;

            if (typeof window.Chart === 'undefined') {
                window.setTimeout(renderDashboardChart, 100);
                return;
            }

            const datasets = [
                { label: 'Sales', data: data.sales, color: '#3867d6' },
                { label: 'Profit', data: data.profit, color: '#159a8c' },
                { label: 'Paid', data: data.paid, color: '#e6a23c' },
                { label: 'Due', data: data.due, color: '#dc3545' },
            ];

            if (window.dashboardYearChart instanceof window.Chart) {
                window.dashboardYearChart.data.labels = data.labels;
                window.dashboardYearChart.data.datasets.forEach((dataset, index) => {
                    dataset.data = datasets[index].data;
                });
                window.dashboardYearChart.update();
                return;
            }

            window.dashboardYearChart = new window.Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: datasets.map((dataset) => ({
                        label: dataset.label,
                        data: dataset.data,
                        borderColor: dataset.color,
                        backgroundColor: dataset.color,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3,
                    })),
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ৳ ${Number(context.raw).toLocaleString()}`,
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => `৳ ${Number(value).toLocaleString()}`,
                            },
                        },
                    },
                },
            });
        };

        renderDashboardChart();

        $wire.on('dashboard-year-selected', (event) => {
            selectedYear = event.detail.year;
            renderDashboardChart();
        });
    })();
</script>
@endscript
