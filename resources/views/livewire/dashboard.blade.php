<div class="dashboard-shell">
    @role('admin')
    <style>
        .dashboard-shell {
            --dashboard-ink: #172033;
            --dashboard-muted: #687386;
            --dashboard-line: #e4e8ef;
            --dashboard-paper: #ffffff;
            --dashboard-wash: #f5f7fb;
            --dashboard-blue: #3867d6;
            --dashboard-teal: #159a8c;
            --dashboard-amber: #d78719;
            --dashboard-red: #d84f5f;
            min-height: calc(100vh - 7rem);
            padding: clamp(1.25rem, 3vw, 2.75rem);
            color: var(--dashboard-ink);
            background:
                radial-gradient(circle at 0 0, rgba(56, 103, 214, .09), transparent 30rem),
                linear-gradient(135deg, #f8fafc 0%, #f2f5fa 100%);
        }

        .dashboard-wrap { width: min(100%, 1500px); margin: 0 auto; }
        .dashboard-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.75rem; }
        .dashboard-kicker { margin: 0 0 .35rem; color: var(--dashboard-blue); font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .dashboard-heading h1 { margin: 0; font-size: clamp(1.65rem, 3vw, 2.35rem); line-height: 1.1; letter-spacing: -.03em; }
        .dashboard-date { padding: .55rem .8rem; border: 1px solid rgba(56, 103, 214, .12); border-radius: 999px; color: var(--dashboard-muted); background: rgba(255, 255, 255, .65); font-size: .78rem; font-weight: 700; white-space: nowrap; }
        .dashboard-period { display: flex; align-items: center; gap: .6rem; margin-top: .75rem; }
        .dashboard-period label { color: var(--dashboard-muted); font-size: .72rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .dashboard-period select { min-width: 10rem; padding: .6rem .8rem; border: 1px solid var(--dashboard-line); border-radius: .7rem; color: var(--dashboard-ink); background: #fff; font: inherit; font-size: .85rem; font-weight: 700; outline: none; }
        .dashboard-period select:focus { border-color: var(--dashboard-blue); box-shadow: 0 0 0 3px rgba(56, 103, 214, .12); }
        .dashboard-grid { display: grid; gap: 1rem; }
        .dashboard-grid--six { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .dashboard-grid--four { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .dashboard-grid--three { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .dashboard-grid--annual { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .dashboard-card { min-width: 0; padding: 1.15rem; border: 1px solid rgba(228, 232, 239, .9); border-radius: 1rem; background: rgba(255, 255, 255, .9); box-shadow: 0 8px 24px rgba(30, 45, 70, .045); transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; }
        .dashboard-card:hover { border-color: rgba(56, 103, 214, .2); box-shadow: 0 14px 32px rgba(30, 45, 70, .09); transform: translateY(-2px); }
        .dashboard-period-summary { margin-bottom: clamp(1.25rem, 3vw, 2rem); }
        .dashboard-financial-card { position: relative; overflow: hidden; padding-top: 1.35rem; }
        .dashboard-financial-card::before { position: absolute; top: 0; right: 0; left: 0; height: .25rem; content: ''; background: var(--dashboard-blue); }
        .dashboard-financial-card--profit::before { background: var(--dashboard-teal); }
        .dashboard-financial-card--due::before { background: var(--dashboard-red); }
        .dashboard-annual-card { padding: 1.25rem; }
        .dashboard-annual-card__year { font-size: 1.35rem; font-weight: 800; letter-spacing: -.03em; }
        .dashboard-annual-card__count { color: var(--dashboard-muted); font-size: .75rem; }
        .dashboard-annual-card__metric { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-top: .9rem; padding-top: .65rem; border-top: 1px solid var(--dashboard-line); font-size: .78rem; }
        .dashboard-annual-card__metric strong { font-size: .9rem; }
        .dashboard-card__top { display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
        .dashboard-label { color: var(--dashboard-muted); font-size: .78rem; font-weight: 700; }
        .dashboard-value { margin-top: .55rem; overflow: hidden; font-size: clamp(1.35rem, 2.2vw, 1.85rem); font-weight: 800; letter-spacing: -.04em; text-overflow: ellipsis; white-space: nowrap; }
        .dashboard-caption { margin-top: .3rem; color: var(--dashboard-muted); font-size: .72rem; }
        .dashboard-icon { display: grid; width: 2.1rem; height: 2.1rem; place-items: center; border-radius: .7rem; font-size: 1rem; font-weight: 800; }
        .dashboard-icon--blue { color: #315bbd; background: #eaf0ff; }
        .dashboard-icon--teal { color: #117d72; background: #e3f6f2; }
        .dashboard-icon--amber { color: #a86512; background: #fff1d9; }
        .dashboard-icon--red { color: #b83c4b; background: #ffeaed; }
        .dashboard-section { margin-top: 1.25rem; }
        .dashboard-section__title { margin: 0 0 .75rem; font-size: 1rem; font-weight: 800; }
        .dashboard-profit { color: #fff; border: 0; background: #243450; box-shadow: 0 12px 28px rgba(36, 52, 80, .18); }
        .dashboard-profit .dashboard-label, .dashboard-profit .dashboard-caption { color: #cbd6e8; }
        .dashboard-profit .dashboard-value { font-size: clamp(1.8rem, 3vw, 2.55rem); }
        .dashboard-chart { min-height: 24rem; padding: 1.35rem; }
        .dashboard-chart__head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
        .dashboard-chart__head h2 { margin: 0; font-size: 1.05rem; font-weight: 800; }
        .dashboard-chart__head span { color: var(--dashboard-muted); font-size: .78rem; }
        .dashboard-chart canvas { width: 100% !important; height: 18rem !important; }
        @media (max-width: 1200px) {
            .dashboard-grid--six { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .dashboard-grid--four { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid--annual { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 640px) {
            .dashboard-shell { padding: 1rem; }
            .dashboard-heading { display: block; margin-bottom: 1.25rem; }
            .dashboard-date { display: block; margin-top: .7rem; }
            .dashboard-grid--six, .dashboard-grid--four, .dashboard-grid--three, .dashboard-grid--annual { grid-template-columns: 1fr; gap: .7rem; }
            .dashboard-card { padding: .9rem; border-radius: .8rem; }
            .dashboard-value { font-size: 1.15rem; }
            .dashboard-icon { width: 1.8rem; height: 1.8rem; font-size: .85rem; }
            .dashboard-card__top { align-items: flex-start; flex-wrap: wrap; }
            .dashboard-period { width: 100%; justify-content: space-between; }
            .dashboard-period select { min-width: 0; flex: 1; }
            .dashboard-chart { min-height: 20rem; padding: 1rem; }
            .dashboard-chart canvas { height: 15rem !important; }
        }
    </style>

    <div class="dashboard-wrap">
        <header class="dashboard-heading">
            <div>
                <p class="dashboard-kicker">Operations overview</p>
                <h1>{{ __('ui.admin_dashboard') }}</h1>
            </div>
            <div class="dashboard-date">{{ now()->format('d M Y') }}</div>
        </header>

        <section class="dashboard-section dashboard-card dashboard-period-summary">
            <div class="dashboard-card__top">
                <div>
                    <h2 class="dashboard-section__title" style="margin-bottom: .25rem;">Selected period</h2>
                    <div class="dashboard-caption">Sales, profit, paid, and due for the selected period</div>
                </div>
                <div class="dashboard-period" style="margin-top: 0;">
                    <label for="dashboard-period">Period</label>
                    <select id="dashboard-period" wire:model.live="period">
                        <option value="today">Today</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="dashboard-grid dashboard-grid--four" style="margin-top: 1rem;">
                <article class="dashboard-card"><div class="dashboard-label">{{ ucfirst($period) }} Sales</div><div class="dashboard-value" style="color: var(--dashboard-blue)">৳ {{ number_format($selectedPeriod['sales'], 2) }}</div></article>
                <article class="dashboard-card dashboard-profit"><div class="dashboard-label">{{ ucfirst($period) }} Profit</div><div class="dashboard-value">৳ {{ number_format($selectedPeriod['profit'], 2) }}</div></article>
                <article class="dashboard-card"><div class="dashboard-label">{{ ucfirst($period) }} Paid</div><div class="dashboard-value" style="color: var(--dashboard-teal)">৳ {{ number_format($selectedPeriod['paid'], 2) }}</div></article>
                <article class="dashboard-card"><div class="dashboard-label">{{ ucfirst($period) }} Due</div><div class="dashboard-value" style="color: var(--dashboard-red)">৳ {{ number_format($selectedPeriod['due'], 2) }}</div></article>
            </div>
        </section>

        <section class="dashboard-grid dashboard-grid--six" aria-label="Business overview">
            @foreach ([
                ['label' => __('ui.total_customers'), 'value' => $totalCustomers, 'icon' => 'C', 'tone' => 'blue'],
                ['label' => __('ui.total_purchases'), 'value' => $totalPurchases, 'icon' => 'P', 'tone' => 'teal'],
                ['label' => __('ui.total_locations'), 'value' => $totalLocations, 'icon' => 'L', 'tone' => 'amber'],
                ['label' => 'Total Products', 'value' => $totalProducts, 'icon' => 'I', 'tone' => 'red'],
            ] as $metric)
                <article class="dashboard-card">
                    <div class="dashboard-card__top">
                        <span class="dashboard-label">{{ $metric['label'] }}</span>
                        <span class="dashboard-icon dashboard-icon--{{ $metric['tone'] }}">{{ $metric['icon'] }}</span>
                    </div>
                    <div class="dashboard-value">{{ number_format($metric['value']) }}</div>
                    <div class="dashboard-caption">Active records</div>
                </article>
            @endforeach

            <article class="dashboard-card">
                <div class="dashboard-card__top"><span class="dashboard-label">Total Sales</span><span class="dashboard-icon dashboard-icon--blue">৳</span></div>
                <div class="dashboard-value">৳ {{ number_format($totalSales, 2) }}</div>
                <div class="dashboard-caption">Customer total price</div>
            </article>
            <article class="dashboard-card dashboard-profit">
                <div class="dashboard-card__top"><span class="dashboard-label">Total Profit</span><span class="dashboard-icon dashboard-icon--teal">+</span></div>
                <div class="dashboard-value">৳ {{ number_format($totalProfit, 2) }}</div>
                <div class="dashboard-caption">Customer total minus shop cost</div>
            </article>
        </section>

        <section class="dashboard-section">
            <h2 class="dashboard-section__title">Cash position</h2>
            <div class="dashboard-grid dashboard-grid--four">
                <article class="dashboard-card"><div class="dashboard-label">Down Payments</div><div class="dashboard-value">৳ {{ number_format($totalDown, 2) }}</div><div class="dashboard-caption">Collected upfront</div></article>
                <article class="dashboard-card"><div class="dashboard-label">Total Paid</div><div class="dashboard-value" style="color: var(--dashboard-teal)">৳ {{ number_format($totalPaid, 2) }}</div><div class="dashboard-caption">Including down payments</div></article>
                <article class="dashboard-card"><div class="dashboard-label">Total Due</div><div class="dashboard-value" style="color: var(--dashboard-red)">৳ {{ number_format($totalDue, 2) }}</div><div class="dashboard-caption">Remaining from net price</div></article>
                <article class="dashboard-card"><div class="dashboard-label">Total Net / Cost</div><div class="dashboard-value" style="color: var(--dashboard-amber)">৳ {{ number_format($totalNet, 2) }}</div><div class="dashboard-caption">Recorded net value</div></article>
            </div>
        </section>

        <section class="dashboard-section">
            <h2 class="dashboard-section__title">Period financials</h2>
            <div class="dashboard-grid dashboard-grid--three">
                @foreach ([['label' => 'Today Sales', 'value' => $today['sales'], 'tone' => 'blue'], ['label' => 'Today Profit', 'value' => $today['profit'], 'tone' => 'teal'], ['label' => 'Today Due', 'value' => $today['due'], 'tone' => 'red']] as $metric)
                    <article class="dashboard-card dashboard-financial-card dashboard-financial-card--{{ $metric['tone'] === 'teal' ? 'profit' : ($metric['tone'] === 'red' ? 'due' : 'sales') }}">
                        <div class="dashboard-label">{{ $metric['label'] }}</div>
                        <div class="dashboard-value" style="color: var(--dashboard-{{ $metric['tone'] }})">৳ {{ number_format($metric['value'], 2) }}</div>
                        <div class="dashboard-caption">Today</div>
                    </article>
                @endforeach
                @foreach ([['label' => 'Month Sales', 'value' => $month['sales'], 'tone' => 'blue'], ['label' => 'Month Profit', 'value' => $month['profit'], 'tone' => 'teal'], ['label' => 'Month Due', 'value' => $month['due'], 'tone' => 'red']] as $metric)
                    <article class="dashboard-card dashboard-financial-card dashboard-financial-card--{{ $metric['tone'] === 'teal' ? 'profit' : ($metric['tone'] === 'red' ? 'due' : 'sales') }}">
                        <div class="dashboard-label">{{ $metric['label'] }}</div>
                        <div class="dashboard-value" style="color: var(--dashboard-{{ $metric['tone'] }})">৳ {{ number_format($metric['value'], 2) }}</div>
                        <div class="dashboard-caption">This month</div>
                    </article>
                @endforeach
                @foreach ([['label' => 'Year Sales', 'value' => $year['sales'], 'tone' => 'blue'], ['label' => 'Year Profit', 'value' => $year['profit'], 'tone' => 'teal'], ['label' => 'Year Due', 'value' => $year['due'], 'tone' => 'red']] as $metric)
                    <article class="dashboard-card dashboard-financial-card dashboard-financial-card--{{ $metric['tone'] === 'teal' ? 'profit' : ($metric['tone'] === 'red' ? 'due' : 'sales') }}">
                        <div class="dashboard-label">{{ $metric['label'] }}</div>
                        <div class="dashboard-value" style="color: var(--dashboard-{{ $metric['tone'] }})">৳ {{ number_format($metric['value'], 2) }}</div>
                        <div class="dashboard-caption">This year</div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="dashboard-section">
            <div class="dashboard-card__top">
                <h2 class="dashboard-section__title">Yearly performance</h2>
                <div class="dashboard-period" style="margin-top: 0;">
                    <label for="dashboard-year">Year</label>
                    <select id="dashboard-year" wire:model.live="selectedYear">
                        @foreach ($annualData as $annual)
                            <option value="{{ $annual['year'] }}">{{ $annual['year'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="dashboard-grid dashboard-grid--four" style="margin-bottom: 1rem;">
                <article class="dashboard-card dashboard-financial-card"><div class="dashboard-label">{{ $selectedAnnual['year'] }} Sales</div><div class="dashboard-value" style="color: var(--dashboard-blue)">৳ {{ number_format($selectedAnnual['sales'], 2) }}</div><div class="dashboard-caption">{{ number_format($selectedAnnual['purchases']) }} sales</div></article>
                <article class="dashboard-card dashboard-financial-card dashboard-financial-card--profit"><div class="dashboard-label">{{ $selectedAnnual['year'] }} Profit</div><div class="dashboard-value" style="color: var(--dashboard-teal)">৳ {{ number_format($selectedAnnual['profit'], 2) }}</div><div class="dashboard-caption">Annual profit</div></article>
                <article class="dashboard-card dashboard-financial-card"><div class="dashboard-label">{{ $selectedAnnual['year'] }} Paid</div><div class="dashboard-value" style="color: var(--dashboard-teal)">৳ {{ number_format($selectedAnnual['paid'], 2) }}</div><div class="dashboard-caption">Collected payments</div></article>
                <article class="dashboard-card dashboard-financial-card dashboard-financial-card--due"><div class="dashboard-label">{{ $selectedAnnual['year'] }} Due</div><div class="dashboard-value" style="color: var(--dashboard-red)">৳ {{ number_format($selectedAnnual['due'], 2) }}</div><div class="dashboard-caption">Remaining balance</div></article>
            </div>
            <div class="dashboard-grid dashboard-grid--annual">
                @foreach ($annualData as $annual)
                    <article class="dashboard-card dashboard-annual-card">
                        <div class="dashboard-card__top">
                            <div class="dashboard-annual-card__year">{{ $annual['year'] }}</div>
                            <div class="dashboard-annual-card__count">{{ number_format($annual['purchases']) }} sales</div>
                        </div>
                        <div class="dashboard-annual-card__metric"><span>Sales</span><strong style="color: var(--dashboard-blue)">৳ {{ number_format($annual['sales'], 2) }}</strong></div>
                        <div class="dashboard-annual-card__metric"><span>Profit</span><strong style="color: var(--dashboard-teal)">৳ {{ number_format($annual['profit'], 2) }}</strong></div>
                        <div class="dashboard-annual-card__metric"><span>Paid</span><strong>৳ {{ number_format($annual['paid'], 2) }}</strong></div>
                        <div class="dashboard-annual-card__metric"><span>Due</span><strong style="color: var(--dashboard-red)">৳ {{ number_format($annual['due'], 2) }}</strong></div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="dashboard-section dashboard-card dashboard-chart" wire:ignore>
            <div class="dashboard-chart__head">
                <h2>Customers and purchases</h2>
                <span>Last six months</span>
            </div>
            <canvas id="dashboardChart"></canvas>
        </section>
    </div>
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
            customerGradient.addColorStop(0, 'rgba(56, 103, 214, .24)');
            customerGradient.addColorStop(1, 'rgba(56, 103, 214, 0)');
            const purchaseGradient = context.createLinearGradient(0, 0, 0, 300);
            purchaseGradient.addColorStop(0, 'rgba(21, 154, 140, .2)');
            purchaseGradient.addColorStop(1, 'rgba(21, 154, 140, 0)');

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
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 1400, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 22, color: '#687386', font: { size: 12, weight: '600' } } },
                        tooltip: { backgroundColor: '#172033', padding: 12, cornerRadius: 8, displayColors: true },
                    },
                    scales: {
                        y: { beginAtZero: true, border: { display: false }, grid: { color: '#edf0f5' }, ticks: { color: '#8a94a6', precision: 0 } },
                        x: { border: { display: false }, grid: { display: false }, ticks: { color: '#8a94a6' } },
                    },
                },
            });
        };

        renderDashboardChart();
        document.addEventListener('livewire:navigated', renderDashboardChart, { once: true });
    })();
</script>
@endscript


