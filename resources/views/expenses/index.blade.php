
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Expenses</h1>
            <p class="text-gray-600">Manage and review business expenses.</p>
        </div>

        <a href="{{ route('expenses.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2">
            + Record Expense
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded p-4 mb-6">
        <p class="text-gray-500">Total Posted Expenses</p>
        <p class="text-2xl font-bold">
            Rs. {{ number_format((float) $totalExpenses, 2) }}
        </p>
    </div>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-3">Expense No.</th>
                    <th class="text-left p-3">Date</th>
                    <th class="text-left p-3">Category</th>
                    <th class="text-left p-3">Description</th>
                    <th class="text-left p-3">Payment</th>
                    <th class="text-left p-3">Entered By</th>
                    <th class="text-right p-3">Amount (Rs.)</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($expenses as $expense)
                    <tr class="border-t">
                        <td class="p-3">{{ $expense->expense_number }}</td>
                        <td class="p-3">
                            {{ $expense->expense_date->format('d-m-Y') }}
                        </td>
                        <td class="p-3">{{ $expense->category }}</td>
                        <td class="p-3">{{ $expense->description }}</td>
                        <td class="p-3">
                            {{ str_replace('_', ' ', ucfirst($expense->payment_method)) }}
                        </td>
                        <td class="p-3">
                            {{ $expense->user?->name ?? 'System' }}
                        </td>
                        <td class="p-3 text-right font-semibold">
                            {{ number_format((float) $expense->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No expenses recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $expenses->links() }}
    </div>
</div>
@endsection
