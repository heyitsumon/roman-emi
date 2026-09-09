<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_uses_the_correct_purchase_and_due_calculations(): void
    {
        Purchase::create([
            'customer_id' => 1,
            'product_id' => 1,
            'model_id' => 1,
            'sales_price' => 1000.00,
            'down_price' => 200.00,
            'net_price' => 800.00,
            'emi_plan' => 4,
        ]);

        $installment = Installment::create([
            'customer_id' => 1,
            'product_id' => 1,
            'purchase_id' => 1,
            'amount' => 600.00,
            'status' => 'pending',
            'due_date' => now()->addMonth(),
        ]);

        InstallmentPayment::create([
            'installment_id' => $installment->id,
            'amount' => 500.00,
            'paid_at' => now(),
        ]);

        $data = app(Dashboard::class)->render()->getData();

        $this->assertSame(1000.0, (float) $data['totalSales']);
        $this->assertSame(800.0, (float) $data['totalNet']);
        $this->assertSame(200.0, (float) $data['totalProfit']);
        $this->assertSame(100.0, (float) $data['totalDue']);
    }
}
