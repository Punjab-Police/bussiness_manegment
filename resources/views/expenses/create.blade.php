
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Record Expense</h1>

        <a href="{{ route('expenses.index') }}"
           class="text-blue-600 hover:underline">
            Back to Expenses
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 text-red-800 p-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('expenses.store') }}"
          class="bg-white shadow rounded p-6 space-y-5">
        @csrf

        <div>
            <label class="block font-medium mb-1">Expense Category</label>
            <input name="category" value="{{ old('category') }}"
                   required maxlength="100"
                   placeholder="Rent, electricity, transport..."
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Description</label>
            <input name="description" value="{{ old('description') }}"
                   required maxlength="255"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Amount</label>
            <input type="number" name="amount" min="0.01" step="0.01"
                   value="{{ old('amount') }}" required
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Expense Date</label>
            <input type="date" name="expense_date"
                   value="{{ old('expense_date', now()->toDateString()) }}"
                   required class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Payment Method</label>
            <select name="payment_method" required
                    class="w-full border rounded p-2">
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cheque">Cheque</option>
                <option value="card">Card</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div>
            <label class="block font-medium mb-1">
                Reference Number (Optional)
            </label>
            <input name="reference_number"
                   value="{{ old('reference_number') }}"
                   maxlength="100"
                   class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Notes (Optional)</label>
            <textarea name="notes" rows="3" maxlength="2000"
                      class="w-full border rounded p-2">{{ old('notes') }}</textarea>
        </div>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white rounded px-5 py-2">
            Save Expense
        </button>
    </form>
</div>
@endsection
