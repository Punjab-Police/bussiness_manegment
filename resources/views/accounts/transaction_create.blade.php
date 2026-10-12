
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl p-6">
    <h1 class="text-2xl font-bold">Record Transaction</h1>
    <p class="mb-6 text-gray-600">
        Account: {{ $account->name }}
    </p>

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('accounts.transactions.store', $account) }}"
          class="space-y-5 rounded bg-white p-6 shadow">
        @csrf

        <div>
            <label class="mb-1 block font-medium">Date</label>
            <input type="date" name="transaction_date"
                   value="{{ old('transaction_date', now()->toDateString()) }}"
                   required class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Transaction Type</label>
            <select name="type" required class="w-full rounded border p-2">
                <option value="deposit">Deposit / Money In</option>
                <option value="withdrawal">Withdrawal / Money Out</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block font-medium">Amount (Rs.)</label>
            <input type="number" name="amount" min="0.01" step="0.01"
                   value="{{ old('amount') }}" required
                   class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Description</label>
            <input name="description" value="{{ old('description') }}"
                   maxlength="255" required class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Reference (Optional)</label>
            <input name="reference" value="{{ old('reference') }}"
                   maxlength="100" class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Notes (Optional)</label>
            <textarea name="notes" rows="3" maxlength="2000"
                      class="w-full rounded border p-2">{{ old('notes') }}</textarea>
        </div>

        <button class="rounded bg-blue-600 px-5 py-2 text-white">
            Save Transaction
        </button>
    </form>
</div>
@endsection
