
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Customer Payment History</h1>
            <p class="text-gray-600">{{ $customer->name }}</p>
        </div>

        <a href="{{ route('customers.payments.create', $customer) }}"
           class="bg-blue-700 text-white px-4 py-2 rounded">
            + Receive Payment
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Method</th>
                    <th class="p-3 text-left">Reference</th>
                    <th class="p-3 text-right">Received</th>
                    <th class="p-3 text-right">Allocated</th>
                    <th class="p-3 text-left">Received By</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr class="border-t">
                        <td class="p-3">
                            {{ \Illuminate\Support\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                        </td>
                        <td class="p-3">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td class="p-3">{{ $payment->reference_number ?: '—' }}</td>
                        <td class="p-3 text-right">{{ number_format($payment->amount, 2) }}</td>
                        <td class="p-3 text-right">
                            {{ number_format($payment->allocations_sum_amount ?? 0, 2) }}
                        </td>
                        <td class="p-3">{{ $payment->user->name ?? 'System' }}</td>
                        <td class="p-3 text-center">
                            <a class="text-blue-600 hover:underline"
                               href="{{ route('customers.payments.show', [$customer, $payment]) }}">
                                View Receipt
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-5 text-center text-gray-500">
                            No customer payments recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection
