@extends('layouts.app')

@section('title', 'Add Customer')

@section('header')
    Add Customer
@endsection

@section('content')

    <div class="max-w-4xl">

        <div class="bg-white rounded-xl shadow-sm border p-6">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-800">
                    Add Customer
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Enter customer information and opening balance.
                </p>
            </div>


            @if ($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200
                        text-red-700 px-4 py-3 rounded-lg">

                    <ul class="list-disc list-inside space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">

                @csrf


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium
                                  text-slate-700 mb-2">
                            Customer Name *
                        </label>

                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium
                                  text-slate-700 mb-2">
                            Company Name
                        </label>

                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                            class="w-full rounded-lg border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium
                                  text-slate-700 mb-2">
                            Phone
                        </label>

                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full rounded-lg border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium
                                  text-slate-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500">
                    </div>


                    <div>
                        <label class="block text-sm font-medium
                                  text-slate-700 mb-2">
                            Opening Balance
                        </label>

                        <input type="number" name="opening_balance"
                            value="{{ old('opening_balance', 0) }}"
                            min="0" step="0.01" required
                            class="w-full rounded-lg border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500">

                        <p class="text-xs text-slate-500 mt-1">
                            Amount currently receivable from this customer.
                        </p>
                    </div>


                    <div>
                        <label class="block text-sm font-medium
                                  text-slate-700 mb-2">
                            Status
                        </label>

                        <select name="status" required
                            class="w-full rounded-lg border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500">

                            <option value="1" @selected(old('status', '1') == '1')>
                                Active
                            </option>

                            <option value="0" @selected(old('status') === '0')>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                <div>

                    <label class="block text-sm font-medium
                              text-slate-700 mb-2">
                        Address
                    </label>

                    <textarea name="address" rows="3"
                        class="w-full rounded-lg border-slate-300
                           focus:border-blue-500
                           focus:ring-blue-500">{{ old('address') }}</textarea>

                </div>


                <div>

                    <label class="block text-sm font-medium
                              text-slate-700 mb-2">
                        Notes
                    </label>

                    <textarea name="notes" rows="3"
                        class="w-full rounded-lg border-slate-300
                           focus:border-blue-500
                           focus:ring-blue-500">{{ old('notes') }}</textarea>

                </div>


                <div class="flex justify-end gap-3">

                    <a href="{{ route('customers.index') }}"
                        class="px-4 py-2 bg-slate-200
                           text-slate-700 rounded-lg
                           hover:bg-slate-300">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600
                           text-white rounded-lg
                           hover:bg-blue-700">
                        Save Customer
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
