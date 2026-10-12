@extends('layouts.app')

@section('title', 'Suppliers')

@section('header')
    Suppliers
@endsection

@section('content')

    <div class="bg-white rounded-xl shadow-sm">

        <div class="p-6 flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-slate-800">
                    Suppliers
                </h2>

                <p class="text-sm text-slate-500">
                    Manage your suppliers and payable relationships.
                </p>
            </div>

            @if (auth()->user()->hasPermission('suppliers.create'))
                <a href="{{ route('suppliers.create') }}"
                    class="px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700">
                    Add Supplier
                </a>
            @endif

        </div>

        @if (session('success'))
            <div class="mx-6 mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-6 mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50 border-y">
                    <tr>
                        <th class="text-left px-6 py-3">Name</th>
                        <th class="text-left px-6 py-3">Company</th>
                        <th class="text-left px-6 py-3">Phone</th>
                        <th class="text-right px-6 py-3">Opening Balance</th>
                        <th class="text-center px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($suppliers as $supplier)
                        <tr class="border-b">

                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $supplier->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $supplier->company_name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $supplier->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                Rs. {{ number_format($supplier->opening_balance, 2) }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                @if ($supplier->status)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                @if (auth()->user()->hasPermission('suppliers.edit'))
                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                        class="text-blue-600 hover:underline">
                                        Edit
                                    </a>
                                @endif

                                @if (auth()->user()->hasPermission('suppliers.delete'))
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="inline"
                                        onsubmit="return confirm('Delete this supplier?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="ml-3 text-red-600 hover:underline">
                                            Delete
                                        </button>

                                    </form>
                                @endif

                                @if (auth()->user()->hasPermission('suppliers.view'))
                                    <a href="{{ route('suppliers.ledger', $supplier) }}"
                                        class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        Ledger
                                    </a>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                No suppliers found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-6">
            {{ $suppliers->links() }}
        </div>

    </div>

@endsection
