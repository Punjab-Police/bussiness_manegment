
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">{{ $account->name }}</h1>
            <p class="text-gray-600">Account transaction history</p>
        </div>

        <a href="{{ route('accounts.transactions.create', $account) }}"
           class="rounded bg-blue-600 px-4 py-2 text-white">
            + Record Transaction
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded bg-white p-4 shadow">
            <p class="text-gray-500">Deposits</p>
            <p class="text-xl font-bold">{{ number_format($deposits, 2) }}</p>
        </div>
        <div class="rounded bg-white p-4 shadow">
            <p class="text-gray-500">Withdrawals</p>
            <p class="text-xl font-bold">{{ number_format($withdrawals, 2) }}</p>
        </div>
        <div class="rounded bg-white p-4 shadow">
            <p class="text-gray-500">Current Balance</p>
            <p class="text-xl font-bold">{{ number_format($balance, 2) }}</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Reference</th>
                    <th class="p-3 text-left">Description</th>
                    <th class="p-3 text-left">Entered By</th>
                    <th class="p-3 text-right">Amount</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr class="border-t">
                        <td class="p-3">
                            {{ $transaction->transaction_date->format('d-m-Y') }}
                        </td>
                        <td class="p-3">{{ ucfirst($transaction->type) }}</td>
                        <td class="p-3">{{ $transaction->reference ?: '—' }}</td>
                        <td class="p-3">{{ $transaction->description }}</td>
                        <td class="p-3">{{ $transaction->user?->name ?? 'System' }}</td>
                        <td class="p-3 text-right">
                            {{ $transaction->type === 'withdrawal' ? '−' : '+' }}
                            {{ number_format((float) $transaction->amount, 2) }}
                        </td>
                        <td class="p-3">{{ ucfirst($transaction->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No transactions recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
@endsection
