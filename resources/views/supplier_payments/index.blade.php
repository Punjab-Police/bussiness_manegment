@extends('layouts.app')

@section('title', 'Supplier Payments')

@section('header')
    Supplier Payments
@endsection

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-slate-800">
                    {{ $supplier->name }}
                </h2>

                <p class="text-sm text-slate-500">
                    Supplier payment history
                </p>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('suppliers.payments.create', $supplier) }}"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    + Record Payment
                </a>

                <a href="{{ route('suppliers.index') }}"
                    class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
                    Suppliers
                </a>

                @if (auth()->user()->hasPermission('suppliers.view'))
                    <a href="{{ route('suppliers.payments.index', $supplier) }}"
                        class="text-blue-600 hover:text-blue-800 font-medium">
                        Payments
                    </a>
                @endif

            </div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-100">
                        <tr>
                            <th class="text-left px-4 py-3">Date</th>
                            <th class="text-right px-4 py-3">Amount</th>
                            <th class="text-left px-4 py-3">Method</th>
                            <th class="text-left px-4 py-3">Reference</th>
                            <th class="text-left px-4 py-3">Entered By</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">

                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50">

                                <td class="px-4 py-3">
                                    {{ $payment->payment_date->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold">
                                    Rs. {{ number_format($payment->amount, 2) }}
                                </td>

                                <td class="px-4 py-3 capitalize">
                                    {{ $payment->payment_method }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $payment->reference_number ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $payment->user?->name ?? 'System' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                                    No payments recorded.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($payments->hasPages())
                <div class="px-4 py-4 border-t">
                    {{ $payments->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
