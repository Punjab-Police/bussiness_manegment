@extends('layouts.app')

@section('title', 'Supplier Payment')

@section('header')
    Supplier Payment
@endsection

@section('content')

    <div class="max-w-3xl">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">

            <div class="px-6 py-5 border-b">
                <h2 class="text-xl font-semibold text-slate-800">
                    Record Supplier Payment
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Supplier:
                    <span class="font-medium text-slate-700">
                        {{ $supplier->name }}
                    </span>
                </p>
            </div>

            <form method="POST" action="{{ route('suppliers.payments.store', $supplier) }}" class="p-6 space-y-6">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Payment Date
                        </label>

                        <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}"
                            required class="w-full rounded-lg border-slate-300">

                        @error('payment_date')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Amount
                        </label>

                        <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}"
                            required class="w-full rounded-lg border-slate-300">

                        @error('amount')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Payment Method
                        </label>

                        <select name="payment_method" required class="w-full rounded-lg border-slate-300">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="cheque">Cheque</option>
                            <option value="online">Online</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Reference Number
                        </label>

                        <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                            placeholder="Cheque / transaction number" class="w-full rounded-lg border-slate-300">
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Notes
                    </label>

                    <textarea name="notes" rows="4" class="w-full rounded-lg border-slate-300" placeholder="Payment notes...">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end gap-3">

                    <a href="{{ route('suppliers.payments.index', $supplier) }}"
                        class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
                        Cancel
                    </a>

                    <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Record Payment
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
