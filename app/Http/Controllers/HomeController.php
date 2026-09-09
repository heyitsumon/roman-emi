<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\InstallmentPayment;
use App\Models\Purchase;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get all purchases with relationships
    $purchases = Purchase::with('installments')->get();

    // Get all installments
    $installments = Installment::all();

    // Calculate totals using the real receivable and actual payment totals.
    $totalPurchase = Purchase::sum('net_price');
    $totalPaid = InstallmentPayment::sum('amount');
    $totalDue = max($totalPurchase - $totalPaid, 0);

    $totalProfit = Purchase::sum('sales_price') - Purchase::sum('net_price');

    return view('home', compact(
        'totalPurchase',
        'totalPaid',
        'totalDue',
        'totalProfit'
    ));

    }
}
