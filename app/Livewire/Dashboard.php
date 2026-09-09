<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public string $period = 'today';
    public ?int $selectedYear = null;

    public function updatedPeriod(string $period): void
    {
        if (! in_array($period, ['today', 'month', 'year'], true)) {
            $this->period = 'today';
        }
    }

    public function render()
    {
        $user = auth()->user();
        $locationIds = $user ? $user->accessibleLocationIds() : [];
        $purchaseQuery = $user
            ? Purchase::whereHas('customer', fn ($query) => $query->whereIn('location_id', $locationIds))
            : Purchase::query();

        // Counts
        $totalCustomers = $user
            ? Customer::whereIn('location_id', $locationIds)->count()
            : Customer::count();
        $totalPurchases = (clone $purchaseQuery)->count();
        $totalLocations = count($locationIds);
        $totalProducts = Product::count();

        // Dashboard accounting: sales are gross sales, while net is the financed cost.
        $purchases = (clone $purchaseQuery)->with('installments.payments')->get();
        $totalSales = round((float) $purchases->sum('sales_price'), 2);
        $totalNet = round((float) $purchases->sum('net_price'), 2);
        $totalDown = round((float) $purchases->sum('down_price'), 2);
        $totalPaid = round((float) $purchases->sum(
            fn ($purchase) => $purchase->installments->sum(
                fn ($installment) => $installment->payments->sum('amount')
            ) + $purchase->down_price
        ), 2);
        $totalDue = round(max($totalNet - $totalPaid, 0), 2);
        $totalProfit = round((float) $purchases->sum(
            fn ($purchase) => (float) $purchase->sales_price - (float) $purchase->net_price
        ), 2);

        $periodData = function (string $period) use ($purchases): array {
            $periodPurchases = $purchases->filter(function ($purchase) use ($period): bool {
                $createdAt = $purchase->created_at;

                return $createdAt && match ($period) {
                    'today' => $createdAt->isToday(),
                    'month' => $createdAt->isSameMonth(now()),
                    'year' => $createdAt->isSameYear(now()),
                    default => false,
                };
            });

            $sales = (float) $periodPurchases->sum('sales_price');
            $profit = (float) $periodPurchases->sum(
                fn ($purchase) => (float) $purchase->sales_price - (float) $purchase->net_price
            );
            $downPayments = (float) $periodPurchases->sum('down_price');
            $now = now();
            $installmentPayments = (float) $purchases->sum(function ($purchase) use ($period, $now): float {
                return (float) $purchase->installments->sum(function ($installment) use ($period, $now): float {
                    return (float) $installment->payments
                        ->filter(function ($payment) use ($period, $now): bool {
                            $paidAt = Carbon::parse($payment->paid_at);

                            return match ($period) {
                                'today' => $paidAt->isToday(),
                                'month' => $paidAt->isSameMonth($now),
                                'year' => $paidAt->isSameYear($now),
                                default => false,
                            };
                        })
                        ->sum('amount');
                });
            });
            $paid = $downPayments + $installmentPayments;

            return [
                'sales' => round($sales, 2),
                'profit' => round($profit, 2),
                'paid' => round($paid, 2),
                'due' => round(max($sales - $paid, 0), 2),
            ];
        };

        $today = $periodData('today');
        $month = $periodData('month');
        $year = $periodData('year');
        $selectedPeriod = $periodData($this->period);

        $annualYears = $purchases
            ->pluck('created_at')
            ->filter()
            ->map(fn ($createdAt) => $createdAt->year)
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();
        $annualData = $annualYears->map(function (int $calendarYear) use ($purchases): array {
            $annualPurchases = $purchases->filter(
                fn ($purchase) => $purchase->created_at?->year === $calendarYear
            );
            $sales = (float) $annualPurchases->sum('sales_price');
            $profit = (float) $annualPurchases->sum(
                fn ($purchase) => (float) $purchase->sales_price - (float) $purchase->net_price
            );
            $downPayments = (float) $annualPurchases->sum('down_price');
            $installmentPayments = (float) $purchases->sum(
                fn ($purchase) => $purchase->installments->sum(
                    fn ($installment) => $installment->payments
                        ->filter(fn ($payment) => Carbon::parse($payment->paid_at)->year === $calendarYear)
                        ->sum('amount')
                )
            );
            $paid = $downPayments + $installmentPayments;

            return [
                'year' => $calendarYear,
                'sales' => round($sales, 2),
                'profit' => round($profit, 2),
                'paid' => round($paid, 2),
                'due' => round(max($sales - $paid, 0), 2),
                'purchases' => $annualPurchases->count(),
            ];
        })->all();
        $availableYears = collect($annualData)->pluck('year')->all();
        if (! in_array($this->selectedYear, $availableYears, true)) {
            $this->selectedYear = $availableYears[0] ?? now()->year;
        }
        $selectedAnnual = collect($annualData)->first(
            fn (array $annual) => $annual['year'] === $this->selectedYear
        ) ?? [
            'year' => $this->selectedYear,
            'sales' => 0,
            'profit' => 0,
            'paid' => 0,
            'due' => 0,
            'purchases' => 0,
        ];

        // Chart Data (Last 6 months)
        $chartLabels = [];
        $customerChartData = [];
        $purchaseChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $chartMonth = now()->subMonths($i);
            $chartLabels[] = $chartMonth->format('M Y');

            $customerQuery = $user
                ? Customer::whereIn('location_id', $locationIds)
                : Customer::query();
            $customerChartData[] = $customerQuery
                ->whereMonth('created_at', $chartMonth->month)
                ->whereYear('created_at', $chartMonth->year)
                ->count();

            $purchaseChartData[] = (clone $purchaseQuery)->whereMonth('created_at', $chartMonth->month)
                ->whereYear('created_at', $chartMonth->year)
                ->count();
        }

        return view('livewire.dashboard', compact(
            'totalCustomers',
            'totalPurchases',
            'totalLocations',
            'totalProducts',
            'totalSales',
            'totalNet',
            'totalDown',
            'totalPaid',
            'totalDue',
            'totalProfit',
            'today',
            'month',
            'year',
            'selectedPeriod',
            'annualData',
            'selectedAnnual',
            'chartLabels',
            'customerChartData',
            'purchaseChartData'
        ));
    }
}
