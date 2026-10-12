@extends('layouts.app')

@section('title', 'Stock Adjustment')

@section('header')
    Stock Adjustment
@endsection

@section('content')

    <div class="max-w-3xl">

        <div class="bg-white shadow-sm rounded-xl p-6">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-800">
                    Adjust Stock
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Use this screen to correct stock after physical stock counting.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('stock.adjustment.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Product --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Product
                        </label>

                        <select name="product_id" required
                            class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Select Product</option>

                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                    ({{ $product->sku }})
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- Difference --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Adjustment Quantity
                        </label>

                        <input type="number" name="difference" step="0.001" required value="{{ old('difference') }}"
                            placeholder="Example: 5 or -3"
                            class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500">

                        <p class="text-xs text-slate-500 mt-1">
                            Use positive number to increase stock and negative number to decrease stock.
                        </p>

                    </div>

                    {{-- Unit Cost --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Unit Cost
                        </label>

                        <input type="number" name="unit_cost" step="0.01" min="0" required
                            value="{{ old('unit_cost', 0) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500">

                    </div>

                    {{-- Date --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Adjustment Date
                        </label>

                        <input type="date" name="movement_date" required
                            value="{{ old('movement_date', now()->toDateString()) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500">

                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Reason / Notes
                        </label>

                        <textarea name="notes" rows="4" required placeholder="Enter the reason for this adjustment..."
                            class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500">{{ old('notes') }}</textarea>

                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 mt-6">

                    <a href="{{ route('stock.index') }}" class="px-5 py-2 bg-gray-100 rounded-lg">
                        Cancel
                    </a>

                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                        Save Adjustment
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
