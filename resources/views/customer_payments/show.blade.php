
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Customer Payment Receipt</h1>
            <p class="text-gray-600">{{ $customer->name }}</p>
        </div>
        <button type="button" onclick="window.print()"
                class="border rounded px-4 py-2 print:hidden">
            Print Receipt
        </button>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Receipt ID:</strong> {{ $payment->id }}</p>
            <p><strong>Date:</strong>
                {{ \Illuminate\Support\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
            </p>
            <p><strong>Customer:</strong> {{ $customer->name }}</p>
            <p><strong>Payment Method:</strong>
                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
            </p>
            <p><strong>Reference:</strong> {{ $payment->reference_number ?: '—' }}</p>
            <p><strong>Received By:</strong> {{ $payment->user->name ?? 'System' }}</p>
        </div>

        <hr class="my-5">

        <h2 class="font-semibold text-lg mb-3">Invoice Allocations</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Invoice</th>
                        <th class="p-3 text-right">Allocated Amount (PKR)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payment->allocations as $allocation)
                        <tr class="border-t">
                            <td class="p-3">
                                {{ $allocation->sale->invoice_number ?? 'Invoice unavailable' }}
                            </td>
                            <td class="p-3 text-right">
                                {{ number_format($allocation->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-3 text-center text-gray-500">
                                This payment has not been allocated to an invoice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @php
            $allocated = (float) $payment->allocations->sum('amount');
        @endphp

        <div class="mt-5 border-t pt-4 space-y-2">
            <p class="flex justify-between">
                <span>Total Received</span>
                <strong>PKR {{ number_format($payment->amount, 2) }}</strong>
            </p>
            <p class="flex justify-between">
                <span>Allocated to Invoices</span>
                <strong>PKR {{ number_format($allocated, 2) }}</strong>
            </p>
            <p class="flex justify-between">
                <span>Unallocated Customer Credit</span>
                <strong>PKR {{ number_format(max(0, (float) $payment->amount - $allocated), 2) }}</strong>
            </p>
        </div>

        @if ($payment->notes)
            <div class="mt-5">
                <strong>Notes:</strong>
                <p>{{ $payment->notes }}</p>
            </div>
        @endif
    </div>

    <a href="{{ route('customers.payments.index', $customer) }}"
       class="inline-block mt-4 text-blue-600 hover:underline print:hidden">
        ← Back to Payment History
    </a>
</div>
@endsection
