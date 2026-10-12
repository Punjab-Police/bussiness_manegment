@extends('layouts.app')

@section('title', 'Customers')

@section('header')
    Customers
@endsection

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">

            <div>
                <h2 class="text-xl font-semibold text-slate-800">
                    Customers
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Manage customers and their account balances.
                </p>
            </div>

            @if (auth()->user()->hasPermission('customers.create'))
                <a href="{{ route('customers.create') }}"
                    class="inline-flex items-center px-4 py-2
                       bg-blue-600 text-white rounded-lg
                       hover:bg-blue-700">
                    + Add Customer
                </a>
            @endif

        </div>


        {{-- Success --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200
                    text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200
                    text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif


        {{-- Customer Table --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 border-b">

                        <tr>

                            <th class="px-6 py-3 text-left">
                                #
                            </th>

                            <th class="px-6 py-3 text-left">
                                Customer
                            </th>

                            <th class="px-6 py-3 text-left">
                                Phone
                            </th>

                            <th class="px-6 py-3 text-right">
                                Opening
                            </th>

                            <th class="px-6 py-3 text-right">
                                Balance
                            </th>

                            <th class="px-6 py-3 text-center">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($customers as $customer)
                            <tr class="border-b hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    {{ $customers->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-800">
                                        {{ $customer->name }}
                                    </div>

                                    @if ($customer->company_name)
                                        <div class="text-xs text-slate-500">
                                            {{ $customer->company_name }}
                                        </div>
                                    @endif

                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->phone ?: '-' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    Rs.
                                    {{ number_format($customer->opening_balance, 2) }}
                                </td>

                                <td class="px-6 py-4 text-right">

                                    <span
                                        class="font-semibold
                                    {{ $customer->balance > 0 ? 'text-red-600' : 'text-green-600' }}">

                                        Rs.
                                        {{ number_format($customer->balance, 2) }}

                                    </span>

                                </td>

                                <td class="px-6 py-4 text-center">

                                    @if ($customer->status)
                                        <span
                                            class="px-2 py-1
                                        text-xs rounded-full
                                        bg-green-100 text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1
                                        text-xs rounded-full
                                        bg-red-100 text-red-700">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td class="px-6 py-4">

                                    <div
                                        class="flex justify-end
                                            items-center gap-3">

                                        @if (auth()->user()->hasPermission('customers.view'))
                                            <a href="{{Route('customers.ledger',$customer)}}"
                                                class="text-indigo-600
                                                   hover:text-indigo-800
                                                   font-medium">
                                                Ledger
                                            </a>
                                        @endif


                                        @if (auth()->user()->hasPermission('customers.edit'))
                                            <a href="{{ route('customers.edit', $customer) }}"
                                                class="text-blue-600
                                                   hover:text-blue-800
                                                   font-medium">
                                                Edit
                                            </a>
                                        @endif


                                        @if (auth()->user()->hasPermission('customers.delete'))
                                            <form method="POST"
                                                action="{{ route('customers.destroy', $customer) }}"
                                                onsubmit="return confirm(
                                                'Are you sure you want to delete this customer?'
                                            );">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="text-red-600
                                                       hover:text-red-800
                                                       font-medium">
                                                    Delete
                                                </button>

                                            </form>
                                        @endif
<a href="{{ route('customers.payments.index', $customer) }}"
   class="text-blue-600 hover:underline">
    Payments
</a>

<a href="{{ route('customers.payments.create', $customer) }}"
   class="text-green-600 hover:underline ml-2">
    Receive Payment
</a>
                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="px-6 py-10 text-center
                                       text-slate-500">
                                    No customers found.
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            @if ($customers->hasPages())
                <div class="p-6 border-t">
                    {{ $customers->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
