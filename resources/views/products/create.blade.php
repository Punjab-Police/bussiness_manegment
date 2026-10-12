@extends('layouts.app')

@section('title', 'Add Product')

@section('header')
    <h1 class="text-2xl font-bold text-slate-800">
        Add Product
    </h1>
@endsection

@section('content')

    <div class="max-w-4xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <form action="{{ route('products.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Category
                    </label>

                    <select name="category_id" class="w-full border rounded-lg px-4 py-2">

                        <option value="">Select Category</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Unit
                    </label>

                    <select name="unit_id" class="w-full border rounded-lg px-4 py-2">

                        <option value="">Select Unit</option>

                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                                @if ($unit->short_name)
                                    ({{ $unit->short_name }})
                                @endif
                            </option>
                        @endforeach

                    </select>

                    @error('unit_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Product Name
                    </label>

                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border rounded-lg px-4 py-2" placeholder="Product name">

                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        SKU
                    </label>

                    <input type="text" name="sku" value="{{ old('sku') }}"
                        class="w-full border rounded-lg px-4 py-2" placeholder="e.g. PROD-001">

                    @error('sku')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Purchase Price
                    </label>

                    <input type="number" name="purchase_price" value="{{ old('purchase_price', 0) }}" step="0.01"
                        min="0" class="w-full border rounded-lg px-4 py-2">

                    @error('purchase_price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Sale Price
                    </label>

                    <input type="number" name="sale_price" value="{{ old('sale_price', 0) }}" step="0.01"
                        min="0" class="w-full border rounded-lg px-4 py-2">

                    @error('sale_price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Minimum Stock
                    </label>

                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" step="0.001"
                        min="0" class="w-full border rounded-lg px-4 py-2">

                    @error('minimum_stock')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Status
                    </label>

                    <select name="status" class="w-full border rounded-lg px-4 py-2">

                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-5">

                <label class="block text-sm font-medium mb-2">
                    Description
                </label>

                <textarea name="description" rows="4" class="w-full border rounded-lg px-4 py-2"
                    placeholder="Optional product description">{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div class="mt-6 flex gap-3">

                <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-lg">
                    Save Product
                </button>

                <a href="{{ route('products.index') }}" class="px-5 py-2 bg-gray-100 rounded-lg">
                    Cancel
                </a>

            </div>

        </form>

    </div>

@endsection
