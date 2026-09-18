<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(
            ['permission:purchase-list|purchase-create|purchase-edit|purchase-delete'],
            ['only' => ['index', 'show']]
        );

        $this->middleware(
            ['permission:purchase-create'],
            ['only' => ['create', 'store']]
        );

        $this->middleware(
            ['permission:purchase-edit'],
            ['only' => ['edit', 'update']]
        );

        $this->middleware(
            ['permission:purchase-delete'],
            ['only' => ['destroy']]
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with([
            'customer.location',
            'product',
            'model'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(100);

        $totalPurchases = Purchase::count();
        $totalNet = Purchase::sum('net_price');
        $totalDown = Purchase::sum('down_price');

        return view('purchases.index', compact(
            'purchases',
            'totalPurchases',
            'totalNet',
            'totalDown'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $locations = Location::all();

        return view('purchases.create', compact(
            'customers',
            'products',
            'locations'
        ));
    }

    /**
     * Get available models for selected product.
     */
    public function getModels($productId)
    {
        $models = ProductModel::where('product_id', $productId)
            ->where('qty', '>', 0)
            ->get([
                'id',
                'model_name',
                'qty'
            ]);

        return response()->json($models);
    }

    /**
     * Customer autocomplete.
     */
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

        $customers = Customer::query()
            ->where('customer_id', 'like', '%' . $term . '%')
            ->orWhere('customer_phone', 'like', '%' . $term . '%')
            ->select(
                'id',
                'customer_id',
                'customer_name'
            )
            ->limit(10)
            ->get();

        $results = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'customer_id' => $customer->customer_id,
                'customer_name' => $customer->customer_name,
            ];
        });

        return response()->json($results);
    }

    /**
     * Store a newly created purchase.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'model_id'    => 'required|exists:product_models,id',

            'sales_price' => 'required|numeric|min:0|lte:net_price',

            // FIX:
            // Down payment cannot be greater than net price.
            'down_price'  => 'required|numeric|min:0|lte:net_price',

            'net_price'   => 'required|numeric|min:0|gte:sales_price',
            'emi_plan'    => 'required|integer|min:1',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Extra safety check
        |--------------------------------------------------------------------------
        */

        if ((float) $request->down_price > (float) $request->net_price) {
            return back()
                ->withInput()
                ->withErrors([
                    'down_price' => 'Down payment cannot be greater than net price.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Product Model
        |--------------------------------------------------------------------------
        */

        $productModel = ProductModel::where('id', $request->model_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (! $productModel) {
            return back()
                ->withInput()
                ->withErrors([
                    'model_id' => 'Selected model is invalid for this product.'
                ]);
        }

        if ($productModel->qty <= 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'model_id' => 'Selected model is out of stock.'
                ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Create Purchase
            |--------------------------------------------------------------------------
            */

            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'product_id'  => $request->product_id,
                'model_id'    => $request->model_id,
                'sales_price' => $request->sales_price,
                'down_price'  => $request->down_price,
                'net_price'   => $request->net_price,
                'emi_plan'    => $request->emi_plan,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reduce Stock
            |--------------------------------------------------------------------------
            */

            $productModel->decrement('qty', 1);

            /*
            |--------------------------------------------------------------------------
            | Calculate EMI
            |--------------------------------------------------------------------------
            */

            $totalDueCents = (int) round(
                ($purchase->net_price - $purchase->down_price) * 100
            );

            /*
            | Extra protection against negative EMI
            */

            if ($totalDueCents < 0) {
                throw new \Exception(
                    'Down payment cannot be greater than net price.'
                );
            }

            $baseEmiCents = intdiv(
                $totalDueCents,
                $purchase->emi_plan
            );

            $remainderCents = $totalDueCents % $purchase->emi_plan;

            /*
            |--------------------------------------------------------------------------
            | Create Installments
            |--------------------------------------------------------------------------
            */

            for ($i = 0; $i < $purchase->emi_plan; $i++) {

                $installmentCents =
                    $baseEmiCents +
                    ($i < $remainderCents ? 1 : 0);

                Installment::create([
                    'customer_id' => $purchase->customer_id,
                    'product_id'  => $purchase->product_id,
                    'purchase_id' => $purchase->id,

                    'amount' => $installmentCents / 100,

                    'status' => 'pending',

                    'due_date' => Carbon::now()
                        ->addMonths($i + 1)
                        ->startOfMonth(),
                ]);
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->to('/customers/' . $purchase->customer_id . '/emi-plans')
                ->with(
                    'success',
                    'Purchase created successfully and EMI plan generated.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $customers = Customer::all();
        $products = Product::all();
        $locations = Location::all();

        $models = ProductModel::where(
            'product_id',
            $purchase->product_id
        )->get();

        return view('purchases.edit', compact(
            'purchase',
            'customers',
            'products',
            'locations',
            'models'
        ));
    }

    /**
     * Update the specified purchase.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'model_id'    => 'required|exists:product_models,id',

            'sales_price' => 'required|numeric|min:0|lte:net_price',

            // FIX:
            // Down payment cannot be greater than net price.
            'down_price'  => 'required|numeric|min:0|lte:net_price',

            'net_price'   => 'required|numeric|min:0|gte:sales_price',
            'emi_plan'    => 'required|integer|min:1',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Extra safety check
        |--------------------------------------------------------------------------
        */

        if ((float) $request->down_price > (float) $request->net_price) {
            return back()
                ->withInput()
                ->withErrors([
                    'down_price' => 'Down payment cannot be greater than net price.'
                ]);
        }

        DB::beginTransaction();

        try {

            $purchase->update([
                'customer_id' => $request->customer_id,
                'product_id'  => $request->product_id,
                'model_id'    => $request->model_id,
                'sales_price' => $request->sales_price,
                'down_price'  => $request->down_price,
                'net_price'   => $request->net_price,
                'emi_plan'    => $request->emi_plan,
            ]);

            DB::commit();

            return redirect()
                ->to('/customers/' . $purchase->customer_id . '/emi-plans')
                ->with(
                    'success',
                    'Purchase updated successfully'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $customerId = $purchase->customer_id;

        $purchase->delete();

        return redirect()
            ->to('/customers/' . $customerId . '/emi-plans')
            ->with(
                'success',
                'Purchase deleted successfully'
            );
    }
}