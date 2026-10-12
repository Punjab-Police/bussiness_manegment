@extends('layouts.app')

@section('title', 'Purchases')

@section('header')
    Purchases
@endsection

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">
                    Purchase History
                </h2>

                <p class="text-sm text-slate-500">
                    View all purchase invoices and their payment status.
                </p>
            </div>

            @if (auth()->user()->hasPermission('purchases.create'))
                <a href="{{ route('purchases.create') }}"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    + New Purchase
                </a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-100 border-b">
                        <tr>
                            <th class="text-left px-4 py-3">Date</th>
                            <th class="text-left px-4 py-3">Invoice</th>
                            <th class="text-left px-4 py-3">Supplier</th>
                            <th class="text-right px-4 py-3">Total</th>
                            <th class="text-right px-4 py-3">Paid</th>
                            <th class="text-right px-4 py-3">Due</th>
                            <th class="text-center px-4 py-3">Status</th>
                            <th class="text-center px-4 py-3">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-slate-50">

                                <td class="px-4 py-3">
                                    {{ $purchase->purchase_date->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3 font-medium text-slate-800">
                                    {{ $purchase->invoice_number }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $purchase->supplier->name }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    Rs. {{ number_format($purchase->total_amount, 2) }}
                                </td>

                                <td class="px-4 py-3 text-right text-green-700">
                                    Rs. {{ number_format($purchase->paid_amount, 2) }}
                                </td>

                                <td
                                    class="px-4 py-3 text-right
                                {{ $purchase->due_amount > 0 ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                                    Rs. {{ number_format($purchase->due_amount, 2) }}
                                </td>

                                <td class="px-4 py-3 text-center">

                                    @if ($purchase->status === 'posted')
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Posted
                                        </span>
                                    @elseif($purchase->status === 'cancelled')
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Cancelled
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    @endif

                                </td>

                                <td class="px-4 py-3 text-center">

                                    <a href="{{ route('purchases.show', $purchase) }}"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-slate-500">
                                    No purchases found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($purchases->hasPages())
                <div class="px-4 py-4 border-t">
                    {{ $purchases->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
