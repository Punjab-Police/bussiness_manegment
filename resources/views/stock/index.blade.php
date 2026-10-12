@extends('layouts.app')

@section('title', 'Stock')

@section('header', 'Stock')


@section('content')


    <div class="space-y-6">
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Stock Ledger
                </h1>

                <p class="text-sm text-slate-500">
                    View all stock movements.
                </p>
            </div>
            <div class="btn">
                @if (auth()->user()->hasPermission('stock.adjust'))
                    <a href="{{ route('stock.opening') }}"
                        class=" mx-4 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                        + Opening Stock
                    </a>
                @endif

                @if (auth()->user()->hasPermission('stock.adjust'))
                    <a href="{{ route('stock.adjustment') }}"
                        class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                        Stock Adjustment
                    </a>
                @endif
            </div>
        </div>

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

                            <th class="px-6 py-3 text-left">
                                Date
                            </th>

                            <th class="px-6 py-3 text-left">
                                Product
                            </th>

                            <th class="px-6 py-3 text-left">
                                Type
                            </th>

                            <th class="px-6 py-3 text-right">
                                Quantity
                            </th>

                            <th class="px-6 py-3 text-right">
                                Unit Cost
                            </th>

                            <th class="px-6 py-3 text-left">
                                User
                            </th>

                            <th class="px-6 py-3 text-left">
                                Notes
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($movements as $movement)
                            <tr class="border-t hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    {{ $movement->movement_date->format('d-m-Y') }}
                                </td>

                                <td class="px-6 py-4 font-medium">
                                    {{ $movement->product->name }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($movement->type === 'opening')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                            Opening
                                        </span>
                                    @elseif($movement->quantity > 0)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                            Stock In
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                            Stock Out
                                        </span>
                                    @endif

                                </td>

                                <td class="px-6 py-4 text-right font-medium">

                                    @if ($movement->quantity > 0)
                                        +{{ number_format($movement->quantity, 3) }}
                                    @else
                                        {{ number_format($movement->quantity, 3) }}
                                    @endif

                                </td>

                                <td class="px-6 py-4 text-right">
                                    Rs. {{ number_format($movement->unit_cost, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $movement->user?->name ?? 'System' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $movement->notes ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">

                                    No stock movements found.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-4">
                {{ $movements->links() }}
            </div>

        </div>
    </div>
@endsection
