@extends('layouts.app')

@section('title', 'Purchase Details')

@section('header')
    Purchase Details
@endsection

@section('content')

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-slate-800">
                    Purchase #{{ $purchase->invoice_number }}
                </h2>

                <p class="text-sm text-slate-500">
                    Complete purchase invoice details.
                </p>
            </div>

            <div class="flex items-center gap-3">

                @if ($purchase->outstanding_amount > 0)
                    <a href="{{ route('suppliers.payments.create', [
                        'supplier' => $purchase->supplier_id,
                        'purchase_id' => $purchase->id,
                    ]) }}"
                        class="inline-flex items-center px-4 py-2
                   bg-green-600 text-white rounded-lg
                   hover:bg-green-700">
                        Pay Due Amount
                    </a>
                @endif

                <a href="{{ route('purchases.index') }}"
                    class="inline-flex items-center px-4 py-2
               bg-slate-200 text-slate-700 rounded-lg
               hover:bg-slate-300">
                    ← Back
                </a>

            </div>


        </div>

        {{-- Purchase Information --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                <h3 class="font-semibold text-slate-800 mb-4">
                    Purchase Information
                </h3>

                <div class="space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-slate-500">Invoice Number</span>
                        <span class="font-medium">
                            {{ $purchase->invoice_number }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Purchase Date</span>
                        <span>
                            {{ $purchase->purchase_date->format('d-m-Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Supplier</span>
                        <span class="font-medium">
                            {{ $purchase->supplier->name }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Created By</span>
                        <span>
                            {{ $purchase->user?->name ?? 'System' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Status</span>
                        <span class="font-medium">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- Payment Summary --}}

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                <h3 class="font-semibold text-slate-800 mb-4">
                    Payment Summary
                </h3>

                <div class="space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span>
                            Rs. {{ number_format($purchase->subtotal, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Discount</span>
                        <span>
                            Rs. {{ number_format($purchase->discount, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Tax</span>
                        <span>
                            Rs. {{ number_format($purchase->tax, 2) }}
                        </span>
                    </div>

                    <div class="border-t pt-3 flex justify-between font-semibold">
                        <span>Total</span>
                        <span>
                            Rs. {{ number_format($purchase->total_amount, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between text-green-700">
                        <span>Paid</span>
                        <span>
                            Rs. {{ number_format($purchase->paid_amount, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between text-red-600 font-semibold">
                        <span>Due</span>
                        <span>
                            Rs. {{ number_format($purchase->due_amount, 2) }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

        {{-- Purchase Items --}}

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-slate-800">
                    Purchase Items
                </h3>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-100">
                        <tr>
                            <th class="text-left px-4 py-3">#</th>
                            <th class="text-left px-4 py-3">Product</th>
                            <th class="text-left px-4 py-3">Unit</th>
                            <th class="text-right px-4 py-3">Quantity</th>
                            <th class="text-right px-4 py-3">Unit Cost</th>
                            <th class="text-right px-4 py-3">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @foreach ($purchase->items as $index => $item)
                            <tr>

                                <td class="px-4 py-3">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $item->product->name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->product->unit->short_name ?? $item->product->unit->name }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    {{ number_format($item->quantity, 3) }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    Rs. {{ number_format($item->unit_cost, 2) }}
                                </td>

                                <td class="px-4 py-3 text-right font-medium">
                                    Rs. {{ number_format($item->total, 2) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Notes --}}

        @if ($purchase->notes)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                <h3 class="font-semibold text-slate-800 mb-2">
                    Notes
                </h3>

                <p class="text-sm text-slate-600">
                    {{ $purchase->notes }}
                </p>

            </div>
        @endif

    </div>

@endsection
