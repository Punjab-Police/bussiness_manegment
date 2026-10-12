@extends('layouts.app')

@section('title', 'Add Supplier')

@section('header')
    Add Supplier
@endsection

@section('content')

    <div class="max-w-4xl">

        <div class="bg-white rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold text-slate-800 mb-6">
                Supplier Information
            </h2>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Contact Person / Supplier Name
                        </label>

                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                            class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Company Name
                        </label>

                        <input type="text" name="company_name"
                            value="{{ old('company_name ', $supplier->company_name) }}"
                            class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Phone
                        </label>

                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                            class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                            class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Opening Balance
                        </label>

                        <input type="number" name="opening_balance" step="0.01" min="0"
                            value="{{ old('opening_balance', $supplier->opening_balance) }}" required
                            class="w-full rounded-lg border-slate-300">

                        <p class="text-xs text-slate-500 mt-1">
                            Amount already payable to this supplier.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Status
                        </label>

                        <select name="status" class="w-full rounded-lg border-slate-300">
                            <option value="1" {{ old('status', $supplier->status) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ old('status', $supplier->status) === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Address
                        </label>

                        <textarea name="address" rows="3" class="w-full rounded-lg border-slate-300">{{ old('address', $supplier->address) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Notes
                        </label>

                        <textarea name="notes" rows="3" class="w-full rounded-lg border-slate-300">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">

                    <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300">
                        Cancel
                    </a>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-slate-800 text-white hover:bg-slate-700">
                        Save Supplier
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
