@extends('layouts.app')

@section('title', 'Supplier Ledger')

@section('header')
    Supplier Ledger
@endsection

@section('content')

    <div class="space-y-6">

        {{-- Supplier Information --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex justify-between items-start">

                <div>
                    <h2 class="text-xl font-semibold text-slate-800">
                        {{ $supplier->name }}
                    </h2>

                    @if ($supplier->company_name)
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $supplier->company_name }}
                        </p>
                    @endif

                    @if ($supplier->phone)
                        <p class="text-sm text-slate-500">
                            {{ $supplier->phone }}
                        </p>
                    @endif
                </div>

                <div class="text-right">
                    <p class="text-sm text-slate-500">
                        Current Balance
                    </p>

                    <p class="text-2xl font-bold
                    {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        Rs. {{ number_format($balance, 2) }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Ledger --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-slate-800">
                    Account Ledger
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Debit represents amounts payable to the supplier.
                    Credit represents payments made.
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">
                        <tr class="border-b">

                            <th class="px-6 py-3 text-left">
                                Date
                            </th>

                            <th class="px-6 py-3 text-left">
                                Type
                            </th>

                            <th class="px-6 py-3 text-left">
                                Reference
                            </th>

                            <th class="px-6 py-3 text-left">
                                Description
                            </th>

                            <th class="px-6 py-3 text-right">
                                Debit
                            </th>

                            <th class="px-6 py-3 text-right">
                                Credit
                            </th>

                            <th class="px-6 py-3 text-right">
                                Balance
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse($entries as $entry)
                            <tr class="border-b hover:bg-slate-50">

                                <td class="px-6 py-3 whitespace-nowrap">
                                    {{ $entry['date'] ? $entry['date']->format('d-m-Y') : '-' }}
                                </td>

                                <td class="px-6 py-3">
                                    <span class="font-medium">
                                        {{ $entry['type'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-3">
                                    {{ $entry['reference'] }}
                                </td>

                                <td class="px-6 py-3 text-slate-600">
                                    {{ $entry['description'] }}
                                </td>

                                <td class="px-6 py-3 text-right">
                                    @if ($entry['debit'] > 0)
                                        <span class="text-red-600 font-medium">
                                            Rs.
                                            {{ number_format($entry['debit'], 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-3 text-right">
                                    @if ($entry['credit'] > 0)
                                        <span class="text-green-600 font-medium">
                                            Rs.
                                            {{ number_format($entry['credit'], 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-3 text-right font-semibold">
                                    Rs.
                                    {{ number_format($entry['balance'], 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                    No ledger transactions found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                    @if ($entries->count())
                        <tfoot class="bg-slate-50 border-t">

                            <tr>
                                <td colspan="6" class="px-6 py-4 text-right font-semibold">
                                    Current Balance
                                </td>

                                <td class="px-6 py-4 text-right font-bold">
                                    Rs.
                                    {{ number_format($balance, 2) }}
                                </td>
                            </tr>

                        </tfoot>
                    @endif

                </table>

            </div>
        </div>

    </div>

@endsection
