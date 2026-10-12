@extends('layouts.app')

@section('title', 'Sales')

@section('header')
    Sales
@endsection

@section('content')
    <div class="space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Sales Invoices</h2>
                <p class="text-sm text-slate-500">Manage your sales and customer receivables.</p>
            </div>

            @if (auth()->user()->hasPermission('sales.create'))
                <a href="{{ route('sales.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    + New Sale
                </a>
            @endif
        </div>

        <div class="bg-white border rounded-xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">Invoice</th>
                        <th class="p-4 text-left">Date</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-right">Total</th>
                        <th class="p-4 text-right">Outstanding</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr class="border-t hover:bg-slate-50">
                            <td class="p-4 font-medium">{{ $sale->invoice_number }}</td>
                            <td class="p-4">{{ $sale->sale_date->format('d-m-Y') }}</td>
                            <td class="p-4">{{ $sale->customer->name }}</td>
                            <td class="p-4 text-right">
                                Rs. {{ number_format($sale->total_amount, 2) }}
                            </td>
                            <td class="p-4 text-right font-semibold">
                                Rs. {{ number_format($sale->outstanding_amount, 2) }}
                            </td>
                            <td class="p-4">{{ ucfirst($sale->status) }}</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                No sales invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
@endsection
