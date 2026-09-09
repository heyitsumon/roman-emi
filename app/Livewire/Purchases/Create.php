<?php

namespace App\Livewire\Purchases;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public string $customerSearch = '';
    public ?int $customer_id = null;
    public ?int $product_id = null;
    public ?int $model_id = null;
    public $sales_price;
    public $down_price;
    public $net_price;
    public $emi_plan;
    public array $models = [];

    protected function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'product_id' => ['required', 'exists:products,id'],
            'model_id' => ['required', 'exists:product_models,id'],
            'sales_price' => ['required', 'numeric', 'min:0', 'lte:net_price'],
            'down_price' => ['required', 'numeric', 'min:0'],
            'net_price' => ['required', 'numeric', 'min:0', 'gte:sales_price'],
            'emi_plan' => ['required', 'integer', 'min:1'],
        ];
    }

    public function updatedProductId(?int $productId): void
    {
        $this->model_id = null;
        $this->models = $productId
            ? ProductModel::query()
                ->where('product_id', $productId)
                ->where('qty', '>', 0)
                ->orderBy('model_name')
                ->get(['id', 'model_name', 'qty'])
                ->toArray()
            : [];
    }

    public function selectCustomer(int $customerId): void
    {
        $customer = Customer::query()
            ->whereKey($customerId)
            ->whereIn('location_id', auth()->user()->accessibleLocationIds())
            ->firstOrFail();

        $this->customer_id = $customer->id;
        $this->customerSearch = $customer->customer_id . ' - ' . $customer->customer_name;
    }

    public function store()
    {
        $data = $this->validate();

        try {
            $purchase = DB::transaction(function () use ($data) {
                $productModel = ProductModel::query()
                    ->whereKey($data['model_id'])
                    ->where('product_id', $data['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $productModel || $productModel->qty <= 0) {
                    throw new \RuntimeException('Selected model is invalid or out of stock.');
                }

                $purchase = Purchase::create($data);
                $productModel->decrement('qty');

                $totalDueCents = (int) round(($purchase->net_price - $purchase->down_price) * 100);
                $baseEmiCents = intdiv($totalDueCents, $purchase->emi_plan);
                $remainderCents = $totalDueCents % $purchase->emi_plan;
                $installments = [];

                for ($month = 0; $month < $purchase->emi_plan; $month++) {
                    $installmentCents = $baseEmiCents + ($month < $remainderCents ? 1 : 0);
                    $installments[] = Installment::create([
                        'customer_id' => $purchase->customer_id,
                        'product_id' => $purchase->product_id,
                        'purchase_id' => $purchase->id,
                        'amount' => $installmentCents / 100,
                        'status' => 'pending',
                        'due_date' => Carbon::now()->addMonths($month + 1)->startOfMonth(),
                    ]);
                }

                return $purchase;
            });
        } catch (\Throwable $exception) {
            report($exception);
            $this->addError('form', 'The purchase could not be saved. Please try again.');
            return;
        }

        session()->flash('success', 'Purchase created successfully and EMI plan generated.');

        return redirect()->to('/customers/' . $purchase->customer_id . '/emi-plans');
    }

    public function render()
    {
        return view('livewire.purchases.create', [
            'customers' => Customer::query()
                ->when($this->customerSearch !== '', function ($query) {
                    $query->where(function ($query) {
                        $query->where('customer_id', 'like', '%' . $this->customerSearch . '%')
                            ->orWhere('customer_name', 'like', '%' . $this->customerSearch . '%')
                            ->orWhere('customer_phone', 'like', '%' . $this->customerSearch . '%');
                    });
                })
                ->whereIn('location_id', auth()->user()->accessibleLocationIds())
                ->orderBy('customer_name')
                ->limit(20)
                ->get(),
            'products' => Product::query()->orderBy('product_name')->get(),
        ])->title('Create Purchase');
    }
}