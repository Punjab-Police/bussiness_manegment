@extends('layouts.app')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')

    <div class="space-y-6">

        {{-- Welcome --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Welcome, {{ auth()->user()->name }}
            </h1>

            <p class="text-gray-500 mt-1">
                Here's an overview of your business management system.
            </p>
        </div>


        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="bg-white rounded-xl shadow-sm p-5 border">
                <p class="text-sm text-gray-500">
                    Total Sales
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    Rs. 0
                </h2>
            </div>


            <div class="bg-white rounded-xl shadow-sm p-5 border">
                <p class="text-sm text-gray-500">
                    Purchases
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    Rs. 0
                </h2>
            </div>


            <div class="bg-white rounded-xl shadow-sm p-5 border">
                <p class="text-sm text-gray-500">
                    Expenses
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    Rs. 0
                </h2>
            </div>


            <div class="bg-white rounded-xl shadow-sm p-5 border">
                <p class="text-sm text-gray-500">
                    Stock Value
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    Rs. 0
                </h2>
            </div>

        </div>


        {{-- Second Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <div class="bg-white rounded-xl shadow-sm p-6 border">

                <h3 class="font-semibold text-gray-900">
                    Receivables
                </h3>

                <p class="text-3xl font-bold mt-3">
                    Rs. 0
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Amount customers owe
                </p>

            </div>


            <div class="bg-white rounded-xl shadow-sm p-6 border">

                <h3 class="font-semibold text-gray-900">
                    Payables
                </h3>

                <p class="text-3xl font-bold mt-3">
                    Rs. 0
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Amount owed to suppliers
                </p>

            </div>


            <div class="bg-white rounded-xl shadow-sm p-6 border">

                <h3 class="font-semibold text-gray-900">
                    Current User
                </h3>

                <p class="font-semibold mt-3">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ auth()->user()->email }}
                </p>

            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">

            <h3 class="font-semibold text-gray-900 mb-4">
                Quick Actions
            </h3>

            <div class="flex flex-wrap gap-3">

                @if (auth()->user()->hasPermission('products.create'))
                    <button class="px-4 py-2 bg-slate-900 text-white rounded-lg">
                        Add Product
                    </button>
                @endif

                @if (auth()->user()->hasPermission('sales.create'))
                    <button class="px-4 py-2 bg-slate-900 text-white rounded-lg">
                        New Sale
                    </button>
                @endif

                @if (auth()->user()->hasPermission('purchases.create'))
                    <button class="px-4 py-2 bg-slate-900 text-white rounded-lg">
                        New Purchase
                    </button>
                @endif

                @if (auth()->user()->hasPermission('expenses.create'))
                    <button class="px-4 py-2 bg-slate-900 text-white rounded-lg">
                        Add Expense
                    </button>
                @endif

            </div>

        </div>

    </div>

@endsection
