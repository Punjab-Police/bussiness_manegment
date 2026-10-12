
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Customer Ledger</h1>
            <p class="text-gray-600">{{ $customer->name }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('customers.payments.index', $customer) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded">
                Payments
            </a>

            <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-700 text-white rounded">
                Print
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded p-4">
            <p class="text-gray-500">Opening Balance</p>
            <p class="text-xl font-bold">
                {{ number_format($openingBalance, 2) }}
            </p>
        </div>

        <div class="bg-white shadow rounded p-4">
            <p class="text-gray-500">Total Sales</p>
            <p class="text-xl font-bold">
                {{ number_format($totalSales, 2) }}
            </p>
        </div>

        <div class="bg-white shadow rounded p-4">
            <p class="text-gray-500">Payments Received</p>
            <p class="text-xl font-bold">
                {{ number_format($totalPayments, 2) }}
            </p>
        </div>

        <div class="bg-white shadow rounded p-4">
            <p class="text-gray-500">Closing Balance</p>
            <p class="text-xl font-bold">
                {{ number_format($runningBalance, 2) }}
            </p>
        </div>
    </div>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Reference</th>
                    <th class="p-3 text-left">Description</th>
                    <th class="p-3 text-right">Debit</th>
                    <th class="p-3 text-right">Credit</th>
                    <th class="p-3 text-right">Balance</th>
                </tr>
            </thead>

            <tbody>
                <tr class="border-t">
                    <td class="p-3">—</td>
                    <td class="p-3">Opening</td>
                    <td class="p-3">—</td>
                    <td class="p-3">Opening balance</td>
                    <td class="p-3 text-right">—</td>
                    <td class="p-3 text-right">—</td>
                    <td class="p-3 text-right font-semibold">
                        {{ number_format($openingBalance, 2) }}
                    </td>
                </tr>

                @forelse ($transactions as $transaction)
                    <tr class="border-t">
                        <td class="p-3">
                            {{ $transaction['date']->format('d-m-Y') }}
                        </td>
                        <td class="p-3">{{ $transaction['type'] }}</td>
                        <td class="p-3">{{ $transaction['reference'] }}</td>
                        <td class="p-3">{{ $transaction['description'] }}</td>
                        <td class="p-3 text-right">
                            {{ $transaction['debit'] > 0
                                ? number_format($transaction['debit'], 2)
                                : '—' }}
                        </td>
                        <td class="p-3 text-right">
                            {{ $transaction['credit'] > 0
                                ? number_format($transaction['credit'], 2)
                                : '—' }}
                        </td>
                        <td class="p-3 text-right font-semibold">
                            {{ number_format($transaction['balance'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No transactions found for this customer.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
    @media print {
        button, a { display: none !important; }
        body { background: white !important; }
    }
</style>
@endsection
