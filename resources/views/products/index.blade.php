@extends('layouts.app')

@section('title', 'Products')

@section('header', 'Product')


@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">
                    Products
                </h1>
                <p class="text-gray-500">
                    Manage products and their pricing.
                </p>
            </div>

            @if (auth()->user()->hasPermission('products.create'))
                <a href="{{ route('products.create') }}"
                    class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                    + Add Product
                </a>
            @endif

        </div>


        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Product</th>
                            <th class="px-6 py-3 text-left">SKU</th>
                            <th class="px-6 py-3 text-left">Category</th>
                            <th class="px-6 py-3 text-left">Unit</th>
                            <th class="px-6 py-3 text-right">Purchase</th>
                            <th class="px-6 py-3 text-right">Sale</th>
                            <th class="px-6 py-3 text-right">Min Stock</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)
                            <tr class="border-t hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    {{ $product->id }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $product->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $product->sku }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $product->category->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $product->unit->short_name ?? $product->unit->name }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    Rs. {{ number_format($product->purchase_price, 2) }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    Rs. {{ number_format($product->sale_price, 2) }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    {{ $product->minimum_stock }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($product->status)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">

                                    @if (auth()->user()->hasPermission('products.edit'))
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="text-blue-600 hover:underline mr-3">
                                            Edit
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasPermission('products.delete'))
                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-600 hover:underline">
                                                Delete
                                            </button>

                                        </form>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10" class="px-6 py-8 text-center text-slate-500">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-4">
                {{ $products->links() }}
            </div>

        </div>
    </div>
@endsection
