@extends('layouts.app')

@section('content')
<style>
    .purchase-index { --ink: #172033; --muted: #6b7688; --line: #e3e8f0; --blue: #3867d6; background: #f5f7fb; margin: -1.5rem -.75rem -3rem; min-height: calc(100vh - 10rem); padding: clamp(1.25rem, 3vw, 2.75rem); color: var(--ink); }
    .purchase-index__wrap { max-width: 1500px; margin: 0 auto; }
    .purchase-index__header { display: flex; align-items: end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
    .purchase-index__kicker { margin: 0 0 .35rem; color: var(--blue); font-size: .72rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .purchase-index h1 { margin: 0; font-size: clamp(1.7rem, 3vw, 2.35rem); letter-spacing: -.04em; }
    .purchase-index__subtitle { margin: .45rem 0 0; color: var(--muted); }
    .purchase-index__add { border: 0; border-radius: .65rem; background: var(--blue); color: #fff; font-weight: 800; padding: .75rem 1rem; text-decoration: none; white-space: nowrap; }
    .purchase-index__add:hover { background: #2e57bd; color: #fff; }
    .purchase-index__stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
    .purchase-index__stat { border: 1px solid var(--line); border-radius: 1rem; background: #fff; padding: 1rem 1.15rem; box-shadow: 0 8px 24px rgba(30, 45, 70, .045); }
    .purchase-index__stat-label { color: var(--muted); font-size: .78rem; font-weight: 700; }
    .purchase-index__stat-value { margin-top: .35rem; font-size: 1.55rem; font-weight: 800; letter-spacing: -.04em; }
    .purchase-index__surface { overflow: hidden; border: 1px solid var(--line); border-radius: 1rem; background: #fff; box-shadow: 0 10px 28px rgba(30, 45, 70, .055); }
    .purchase-index__surface-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--line); }
    .purchase-index__surface-head h2 { margin: 0; font-size: 1rem; font-weight: 800; }
    .purchase-index__surface-head span { color: var(--muted); font-size: .78rem; }
    .purchase-index__table-wrap { overflow-x: auto; }
    .purchase-index table { min-width: 1050px; margin: 0; }
    .purchase-index thead th { padding: .85rem 1rem; border-bottom: 1px solid var(--line); color: var(--muted); font-size: .7rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap; }
    .purchase-index tbody td { padding: .9rem 1rem; border-bottom: 1px solid #eef1f5; vertical-align: middle; }
    .purchase-index tbody tr:last-child td { border-bottom: 0; }
    .purchase-index tbody tr:hover { background: #f8faff; }
    .purchase-index__customer { font-weight: 800; }
    .purchase-index__muted { color: var(--muted); font-size: .78rem; }
    .purchase-index__badge { display: inline-block; padding: .3rem .55rem; border-radius: .45rem; background: #eef3ff; color: #315bbd; font-size: .75rem; font-weight: 750; white-space: nowrap; }
    .purchase-index__actions { display: flex; gap: .4rem; white-space: nowrap; }
    .purchase-index__actions .btn { border-radius: .5rem; font-size: .78rem; font-weight: 700; }
    .purchase-index__empty { padding: 3rem 1rem !important; color: var(--muted); text-align: center; }
    .purchase-index__pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--line); }
    @media (max-width: 640px) { .purchase-index { margin: -1rem -.75rem -2rem; padding: 1rem; } .purchase-index__header { display: block; } .purchase-index__add { display: inline-block; margin-top: 1rem; } .purchase-index__stats { grid-template-columns: 1fr; gap: .7rem; } .purchase-index__surface-head { align-items: start; flex-direction: column; } }
+</style>

<div class="purchase-index">
    <div class="purchase-index__wrap">
        <header class="purchase-index__header">
            <div><p class="purchase-index__kicker">Sales workspace</p><h1>ক্রয় তালিকা</h1><p class="purchase-index__subtitle">Review customer purchases, payment plans, and collection details.</p></div>
            @can('purchase-create')<a href="{{ route('purchases.create') }}" class="purchase-index__add">+ নতুন ক্রয়</a>@endcan
        </header>

        @if (session('success'))<div class="alert alert-success mb-4">{{ session('success') }}</div>@endif

        <section class="purchase-index__stats">
            <article class="purchase-index__stat"><div class="purchase-index__stat-label">Total purchases</div><div class="purchase-index__stat-value">{{ number_format($totalPurchases) }}</div></article>
            <article class="purchase-index__stat"><div class="purchase-index__stat-label">Total net value</div><div class="purchase-index__stat-value">৳ {{ number_format($totalNet, 2) }}</div></article>
            <article class="purchase-index__stat"><div class="purchase-index__stat-label">Down payments</div><div class="purchase-index__stat-value">৳ {{ number_format($totalDown, 2) }}</div></article>
        </section>

        <section class="purchase-index__surface">
            <div class="purchase-index__surface-head"><h2>Recent purchases</h2><span>{{ $purchases->total() }} records</span></div>
            <div class="purchase-index__table-wrap">
                <table class="table align-middle text-start">
                    <thead><tr><th>#</th><th>Customer</th><th>Product</th><th>Location</th><th>Price</th><th>Down payment</th><th>EMI</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($purchases as $index => $purchase)
                        <tr>
                            <td class="purchase-index__muted">{{ $purchases->firstItem() + $index }}</td>
                            <td><div class="purchase-index__customer">{{ $purchase->customer->customer_name ?? 'N/A' }}</div><div class="purchase-index__muted">{{ $purchase->customer->customer_phone ?? 'N/A' }}</div></td>
                            <td><div class="purchase-index__customer">{{ $purchase->product->product_name ?? 'N/A' }}</div><span class="purchase-index__badge">{{ $purchase->model->model_name ?? 'No model' }}</span></td>
                            <td class="purchase-index__muted">{{ $purchase->customer->location->name ?? 'N/A' }}</td>
                            <td><strong>৳ {{ number_format($purchase->net_price, 2) }}</strong><div class="purchase-index__muted">Owner: ৳ {{ number_format($purchase->sales_price, 2) }}</div></td>
                            <td>৳ {{ number_format($purchase->down_price, 2) }}</td><td>{{ $purchase->emi_plan }} months</td>
                            <td><div class="purchase-index__actions">@can('purchase-edit')<a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>@endcan @can('purchase-delete')<form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>@endcan</div></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="purchase-index__empty">কোনো ক্রয় পাওয়া যায়নি।</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="purchase-index__pagination">{{ $purchases->links() }}</div>
        </section>
    </div>
</div>
@endsection
