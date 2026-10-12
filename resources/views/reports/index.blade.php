
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">Financial Reports</h1>
        <p class="text-gray-600">Business financial overview</p>
    </div>

    <form method="GET" action="{{ route('reports.index') }}"
          class="mb-6 flex flex-wrap items-end gap-4 rounded bg-white p-4 shadow">

        <div>
            <label class="mb-1 block text-sm font-medium">From Date</label>
            <input type="date" name="from" value="{{ $from }}"
                   required class="rounded border p-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">To Date</label>
            <input type="date" name="to" value="{{ $to }}"
                   required class="rounded border p-2">
        </div>

        <button class="rounded bg-blue-600 px-4 py-2 text-white">
            Apply Filter
        </button>
    </form>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <div class="rounded bg-white p-5 shadow">
            <p class="text-gray-500">Sales</p>
            <p class="mt-2 text-2xl font-bold">
                Rs. {{ number_format($totalSales, 2) }}
            </p>
            <p class="text-sm text-gray-500">{{ $from }} to {{ $to }}</p>
        </div>

        <div class="rounded bg-white p-5 shadow">
            <p class="text-gray-500">Purchases</p>
            <p class="mt-2 text-2xl font-bold">
                Rs. {{ number_format($totalPurchases, 2) }}
            </p>
        </div>

        <div class="rounded bg-white p-5 shadow">
            <p class="text-gray-500">Expenses</p>
            <p class="mt-2 text-2xl font-bold">
                Rs. {{ number_format($totalExpenses, 2) }}
            </p>
        </div>

        <div class="rounded bg-white p-5 shadow">
            <p class="text-gray-500">Customer Receivables</p>
            <p class="mt-2 text-2xl font-bold">
                Rs. {{ number_format($receivables, 2) }}
            </p>
            <p class="text-sm text-gray-500">All posted invoices</p>
        </div>

        <div class="rounded bg-white p-5 shadow">
            <p class="text-gray-500">Supplier Payables</p>
            <p class="mt-2 text-2xl font-bold">
                Rs. {{ number_format($payables, 2) }}
            </p>
            <p class="text-sm text-gray-500">All posted invoices</p>
        </div>

        <div class="rounded bg-white p-5 shadow">
            <p class="text-gray-500">Cash & Bank Balance</p>
            <p class="mt-2 text-2xl font-bold">
                Rs. {{ number_format($accountBalances, 2) }}
            </p>
            <p class="text-sm text-gray-500">Active accounts</p>
        </div>

    </div>

    <div class="mt-6 rounded bg-white p-5 shadow">
        <h2 class="mb-2 text-lg font-semibold">Preliminary Operating Difference</h2>
        <p class="text-2xl font-bold">
            Rs. {{ number_format($totalSales - $totalPurchases - $totalExpenses, 2) }}
        </p>
        <p class="mt-2 text-sm text-gray-500">
            This is a simple sales-minus-purchases-minus-expenses figure,
            not a formal net profit calculation. A proper profit and loss report
            requires cost-of-goods-sold and inventory valuation.
        </p>
    </div>

</div>
@endsection
