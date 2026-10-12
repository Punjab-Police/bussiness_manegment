
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Cash & Bank Accounts</h1>
            <p class="text-gray-600">Manage account balances and transactions.</p>
        </div>

        <a href="{{ route('accounts.create') }}"
           class="rounded bg-blue-600 px-4 py-2 text-white">
            + Add Account
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($accounts as $account)
            @php
                $deposits = (float) ($account->deposits_total ?? 0);
                $withdrawals = (float) ($account->withdrawals_total ?? 0);
                $balance = (float) $account->opening_balance
                    + $deposits - $withdrawals;
            @endphp

            <div class="rounded-lg bg-white p-5 shadow">
                <div class="flex justify-between gap-3">
                    <h2 class="text-lg font-bold">{{ $account->name }}</h2>
                    <span class="text-sm text-gray-500">
                        {{ ucfirst($account->type) }}
                    </span>
                </div>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $account->account_number ?: 'No account number' }}
                </p>

                <p class="mt-4 text-sm text-gray-500">Current Balance</p>
                <p class="text-2xl font-bold">
                    Rs. {{ number_format($balance, 2) }}
                </p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('accounts.transactions', $account) }}"
                       class="text-blue-600 hover:underline">
                        Transactions
                    </a>

                    <a href="{{ route('accounts.transactions.create', $account) }}"
                       class="text-green-600 hover:underline">
                        Record Transaction
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No accounts have been created yet.</p>
        @endforelse
    </div>
</div>
@endsection
