@extends('layouts.app')

@section('title', 'Sales Invoice')

@section('header')
    Sales Invoice
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border rounded-xl p-6">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h2 class="text-2xl font-bold">
                        Invoice {{ $sale->invoice_number }}
                    </h2>
                    <p class="text-slate-500 mt-1">
                        Date: {{ $sale->sale_date->format('d-m-Y') }}
                    </p>
                    <p class="text-slate-700 mt-2">
                        Customer: {{ $sale->customer->name }}
                    </p>
                </div>

                <a href="{{ route('sales.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg hover:bg-slate-300">
                    ← Back
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-sm text-slate-500">Invoice Total</p>
                    <p class="text-xl font-bold">
                        Rs. {{ number_format($sale->total_amount, 2) }}
                    </p>
                </div>

                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-sm text-slate-500">Allocated Payments</p>
                    <p class="text-xl font-bold text-green-700">
                        Rs. {{ number_format($sale->getAllocatedAmount(), 2) }}
                    </p>
                </div>

                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-sm text-slate-500">Outstanding</p>
                    <p class="text-xl font-bold text-red-600">
                        Rs. {{ number_format($sale->outstanding_amount, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">Product</th>
                        <th class="p-4 text-right">Quantity</th>
                        <th class="p-4 text-right">Unit Price</th>
                        <th class="p-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                        <tr class="border-t">
                            <td class="p-4">
                                {{ $item->product->name }}
                            </td>
                            <td class="p-4 text-right">
                                {{ number_format($item->quantity, 3) }}
                                {{ $item->product->unit->short_name ?? '' }}
                            </td>
                            <td class="p-4 text-right">
                                Rs. {{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="p-4 text-right font-medium">
                                Rs. {{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-6 border-t space-y-3 ml-auto max-w-sm">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rs. {{ number_format($sale->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Discount</span>
                    <span>Rs. {{ number_format($sale->discount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax</span>
                    <span>Rs. {{ number_format($sale->tax, 2) }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>Rs. {{ number_format($sale->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
