
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl p-6">
    <h1 class="mb-6 text-2xl font-bold">Add Cash / Bank Account</h1>

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-4 text-red-800">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('accounts.store') }}"
          class="space-y-5 rounded bg-white p-6 shadow">
        @csrf

        <div>
            <label class="mb-1 block font-medium">Account Name</label>
            <input name="name" value="{{ old('name') }}" required maxlength="150"
                   class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Account Type</label>
            <select name="type" required class="w-full rounded border p-2">
                <option value="cash">Cash</option>
                <option value="bank">Bank</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block font-medium">Account Number (Optional)</label>
            <input name="account_number" value="{{ old('account_number') }}"
                   maxlength="100" class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Opening Balance (Rs.)</label>
            <input type="number" name="opening_balance" min="0" step="0.01"
                   value="{{ old('opening_balance', '0.00') }}" required
                   class="w-full rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block font-medium">Notes (Optional)</label>
            <textarea name="notes" rows="3" maxlength="2000"
                      class="w-full rounded border p-2">{{ old('notes') }}</textarea>
        </div>

        <button class="rounded bg-blue-600 px-5 py-2 text-white">
            Save Account
        </button>
    </form>
</div>
@endsection
