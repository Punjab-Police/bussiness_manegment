@extends('layouts.app')

@section('title', 'Opening Stock')

@section('header')

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Opening Stock
        </h1>

        <p class="text-sm text-slate-500">
            Enter the stock available when starting the system.
        </p>
    </div>

@endsection

@section('content')

    <div class="max-w-3xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        @if ($errors->any())

            <div class="mb-5 px-4 py-3 bg-red-100 text-red-800 rounded-lg">

                <ul class="list-disc list-inside">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        <form action="{{ route('stock.opening.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium mb-2">
                        Product
                    </label>

                    <select name="product_id" class="w-full border rounded-lg px-4 py-2">

                        <option value="">
                            Select Product
                        </option>

                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>

                                {{ $product->name }}
                                — {{ $product->sku }}
                                ({{ $product->unit->short_name ?? $product->unit->name }})
                            </option>
                        @endforeach

                    </select>

                    @error('product_id')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Opening Quantity
                    </label>

                    <input type="number" name="quantity" value="{{ old('quantity') }}" step="0.001" min="0.001"
                        class="w-full border rounded-lg px-4 py-2" placeholder="e.g. 100">

                    @error('quantity')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Unit Cost
                    </label>

                    <input type="number" name="unit_cost" value="{{ old('unit_cost', 0) }}" step="0.01" min="0"
                        class="w-full border rounded-lg px-4 py-2" placeholder="e.g. 250">

                    @error('unit_cost')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Movement Date
                    </label>

                    <input type="date" name="movement_date" value="{{ old('movement_date', now()->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-4 py-2">

                    @error('movement_date')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium mb-2">
                        Notes
                    </label>

                    <textarea name="notes" rows="3" class="w-full border rounded-lg px-4 py-2" placeholder="Optional note">{{ old('notes') }}</textarea>

                    @error('notes')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

            <div class="mt-6 flex gap-3">

                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">

                    Save Opening Stock

                </button>

                <a href="{{ route('stock.index') }}" class="px-5 py-2 bg-slate-200 rounded-lg">

                    Cancel

                </a>

            </div>

        </form>

    </div>

@endsection
