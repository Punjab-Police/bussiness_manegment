@extends('layouts.app')

@section('title', 'Stock Summary')

@section('header')

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Stock Summary
        </h1>

        <p class="text-sm text-slate-500">
            Current inventory balance and stock status.
        </p>
    </div>

@endsection

@section('content')

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-6 py-3 text-left">
                            #
                        </th>

                        <th class="px-6 py-3 text-left">
                            Product
                        </th>

                        <th class="px-6 py-3 text-left">
                            SKU
                        </th>

                        <th class="px-6 py-3 text-left">
                            Category
                        </th>

                        <th class="px-6 py-3 text-left">
                            Unit
                        </th>

                        <th class="px-6 py-3 text-right">
                            Current Stock
                        </th>

                        <th class="px-6 py-3 text-right">
                            Minimum Stock
                        </th>

                        <th class="px-6 py-3 text-left">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($products as $product)
                        @php
                            $stock = $product->current_stock;
                            $minimum = (float) $product->minimum_stock;
                        @endphp

                        <tr class="border-t hover:bg-slate-50">

                            <td class="px-6 py-4">
                                {{ $product->id }}
                            </td>

                            <td class="px-6 py-4 font-medium">
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

                            <td class="px-6 py-4 text-right font-semibold">
                                {{ number_format($stock, 3) }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                {{ number_format($minimum, 3) }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($stock <= 0)
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        Out of Stock
                                    </span>
                                @elseif($stock <= $minimum)
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        In Stock
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">

                                No active products found.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
