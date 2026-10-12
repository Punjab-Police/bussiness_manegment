@extends('layouts.app')

@section('title', 'New Purchase')

@section('header')
    New Purchase
@endsection

@section('content')

    <div class="space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div id="success-message"
                class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 flex items-start justify-between">

                <div>
                    <p class="font-semibold">
                        Success
                    </p>

                    <p class="text-sm mt-1">
                        {{ session('success') }}
                    </p>
                </div>

                <button type="button" onclick="document.getElementById('success-message').remove()"
                    class="ml-4 text-green-600 hover:text-green-800 text-xl leading-none">
                    &times;
                </button>

            </div>
        @endif


        {{-- Error Message --}}
        @if (session('error'))
            <div id="error-message"
                class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 flex items-start justify-between">

                <div>
                    <p class="font-semibold">
                        Error
                    </p>

                    <p class="text-sm mt-1">
                        {{ session('error') }}
                    </p>
                </div>

                <button type="button" onclick="document.getElementById('error-message').remove()"
                    class="ml-4 text-red-600 hover:text-red-800 text-xl leading-none">
                    &times;
                </button>

            </div>
        @endif


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div id="validation-errors" class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="font-semibold mb-2">
                            Please fix the following errors:
                        </p>

                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <button type="button" onclick="document.getElementById('validation-errors').remove()"
                        class="ml-4 text-red-600 hover:text-red-800 text-xl leading-none">
                        &times;
                    </button>

                </div>

            </div>
        @endif


        {{-- Purchase Form --}}
        <form method="POST" action="{{ route('purchases.store') }}">
            @csrf


            {{-- Purchase Header --}}
            <div class="bg-white rounded-xl shadow-sm p-6">

                <h2 class="text-lg font-semibold text-slate-800 mb-5">
                    Purchase Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Supplier --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Supplier
                        </label>

                        <select name="supplier_id" required class="w-full rounded-lg border-slate-300">

                            <option value="">
                                Select Supplier
                            </option>

                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>

                                    {{ $supplier->name }}

                                    @if ($supplier->company_name)
                                        - {{ $supplier->company_name }}
                                    @endif

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- Invoice Number --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Invoice Number
                        </label>

                        <input type="text" name="invoice_number" value="{{ old('invoice_number') }}"
                            placeholder="Supplier invoice number" required class="w-full rounded-lg border-slate-300">

                    </div>


                    {{-- Purchase Date --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Purchase Date
                        </label>

                        <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->toDateString()) }}"
                            required class="w-full rounded-lg border-slate-300">

                    </div>

                </div>

            </div>


            {{-- Purchase Items --}}
            <div class="bg-white rounded-xl shadow-sm p-6">

                <div class="flex items-center justify-between mb-5">

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Purchase Items
                        </h2>

                        <p class="text-sm text-slate-500">
                            Add products purchased from the supplier.
                        </p>

                    </div>


                    <button type="button" id="add-item"
                        class="px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700">

                        + Add Product

                    </button>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 border-y">

                            <tr>

                                <th class="text-left px-3 py-3 min-w-[250px]">
                                    Product
                                </th>

                                <th class="text-right px-3 py-3 min-w-[130px]">
                                    Quantity
                                </th>

                                <th class="text-right px-3 py-3 min-w-[150px]">
                                    Unit Cost
                                </th>

                                <th class="text-right px-3 py-3 min-w-[150px]">
                                    Total
                                </th>

                                <th class="px-3 py-3 w-16">
                                </th>

                            </tr>

                        </thead>


                        <tbody id="purchase-items">

                            <tr class="purchase-row border-b">

                                {{-- Product --}}
                                <td class="px-3 py-3">

                                    <select name="items[0][product_id]" required
                                        class="product-select w-full rounded-lg border-slate-300">

                                        <option value="">
                                            Select Product
                                        </option>

                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-cost="{{ $product->purchase_price }}"
                                                {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>

                                                {{ $product->name }}
                                                ({{ $product->sku }})
                                            </option>
                                        @endforeach

                                    </select>

                                </td>


                                {{-- Quantity --}}
                                <td class="px-3 py-3">

                                    <input type="number" name="items[0][quantity]"
                                        value="{{ old('items.0.quantity', 1) }}" min="0.001" step="0.001" required
                                        class="quantity w-full text-right rounded-lg border-slate-300">

                                </td>


                                {{-- Unit Cost --}}
                                <td class="px-3 py-3">

                                    <input type="number" name="items[0][unit_cost]"
                                        value="{{ old('items.0.unit_cost', 0) }}" min="0" step="0.01" required
                                        class="unit-cost w-full text-right rounded-lg border-slate-300">

                                </td>


                                {{-- Line Total --}}
                                <td class="px-3 py-3">

                                    <input type="text" value="0.00" readonly
                                        class="line-total w-full text-right rounded-lg bg-slate-50 border-slate-300">

                                </td>


                                {{-- Remove --}}
                                <td class="px-3 py-3 text-center">

                                    <button type="button" class="remove-item text-red-600 hover:text-red-800">

                                        Remove

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Totals --}}
            <div class="bg-white rounded-xl shadow-sm p-6">

                <div class="max-w-md ml-auto space-y-4">

                    {{-- Subtotal --}}
                    <div class="flex justify-between">

                        <span class="text-slate-600">
                            Subtotal
                        </span>

                        <span id="subtotal" class="font-medium">

                            Rs. 0.00

                        </span>

                    </div>


                    {{-- Discount --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Discount
                        </label>

                        <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}"
                            min="0" step="0.01" class="w-full rounded-lg border-slate-300 text-right">

                    </div>


                    {{-- Tax --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Tax
                        </label>

                        <input type="number" name="tax" id="tax" value="{{ old('tax', 0) }}" min="0"
                            step="0.01" class="w-full rounded-lg border-slate-300 text-right">

                    </div>


                    {{-- Grand Total --}}
                    <div class="border-t pt-4 flex justify-between text-lg">

                        <span class="font-semibold">
                            Total
                        </span>

                        <span id="grand-total" class="font-bold">

                            Rs. 0.00

                        </span>

                    </div>


                    {{-- Paid Amount --}}
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Paid Amount
                        </label>

                        <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', 0) }}"
                            min="0" step="0.01" class="w-full rounded-lg border-slate-300 text-right">

                    </div>


                    {{-- Due --}}
                    <div class="flex justify-between">

                        <span class="text-slate-600">
                            Payable / Due
                        </span>

                        <span id="due-amount" class="font-semibold text-red-600">

                            Rs. 0.00

                        </span>

                    </div>

                </div>

            </div>


            {{-- Notes + Actions --}}
            <div class="bg-white rounded-xl shadow-sm p-6">

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Notes
                </label>

                <textarea name="notes" rows="3" class="w-full rounded-lg border-slate-300"
                    placeholder="Optional purchase notes...">{{ old('notes') }}</textarea>


                <div class="flex justify-end gap-3 mt-6">

                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-lg border border-slate-300">

                        Cancel

                    </a>


                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-slate-800 text-white hover:bg-slate-700">

                        Save Purchase

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const itemsContainer =
                document.getElementById('purchase-items');

            const addItemButton =
                document.getElementById('add-item');

            let rowIndex = 1;


            /*
            |--------------------------------------------------------------------------
            | Calculate Totals
            |--------------------------------------------------------------------------
            */

            function calculateTotals() {

                let subtotal = 0;


                document.querySelectorAll('.purchase-row').forEach(function(row) {

                    const quantityInput =
                        row.querySelector('.quantity');

                    const unitCostInput =
                        row.querySelector('.unit-cost');

                    const lineTotalInput =
                        row.querySelector('.line-total');


                    const quantity =
                        parseFloat(quantityInput.value) || 0;

                    const unitCost =
                        parseFloat(unitCostInput.value) || 0;


                    const total =
                        quantity * unitCost;


                    lineTotalInput.value =
                        total.toFixed(2);


                    subtotal += total;

                });


                const discount =
                    parseFloat(
                        document.getElementById('discount').value
                    ) || 0;


                const tax =
                    parseFloat(
                        document.getElementById('tax').value
                    ) || 0;


                const paid =
                    parseFloat(
                        document.getElementById('paid_amount').value
                    ) || 0;


                const grandTotal =
                    Math.max(
                        0,
                        subtotal - discount + tax
                    );


                const due =
                    Math.max(
                        0,
                        grandTotal - paid
                    );


                document.getElementById('subtotal').textContent =
                    'Rs. ' + subtotal.toFixed(2);


                document.getElementById('grand-total').textContent =
                    'Rs. ' + grandTotal.toFixed(2);


                document.getElementById('due-amount').textContent =
                    'Rs. ' + due.toFixed(2);

            }


            /*
            |--------------------------------------------------------------------------
            | Bind Row Events
            |--------------------------------------------------------------------------
            */

            function bindRow(row) {

                const productSelect =
                    row.querySelector('.product-select');

                const quantity =
                    row.querySelector('.quantity');

                const unitCost =
                    row.querySelector('.unit-cost');

                const removeButton =
                    row.querySelector('.remove-item');


                /*
                | Product Changed
                */

                productSelect.addEventListener('change', function() {

                    const selected =
                        this.options[this.selectedIndex];


                    const cost =
                        selected.dataset.cost || 0;


                    unitCost.value =
                        cost;


                    calculateTotals();

                });


                /*
                | Quantity Changed
                */

                quantity.addEventListener(
                    'input',
                    calculateTotals
                );


                /*
                | Unit Cost Changed
                */

                unitCost.addEventListener(
                    'input',
                    calculateTotals
                );


                /*
                | Remove Row
                */

                removeButton.addEventListener('click', function() {

                    const rows =
                        document.querySelectorAll('.purchase-row');


                    if (rows.length === 1) {

                        alert(
                            'At least one product is required.'
                        );

                        return;

                    }


                    row.remove();


                    calculateTotals();

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Bind First Row
            |--------------------------------------------------------------------------
            */

            const firstRow =
                document.querySelector('.purchase-row');


            if (firstRow) {
                bindRow(firstRow);
            }


            /*
            |--------------------------------------------------------------------------
            | Add New Product
            |--------------------------------------------------------------------------
            */

            addItemButton.addEventListener('click', function() {

                const firstRow =
                    document.querySelector('.purchase-row');


                const newRow =
                    firstRow.cloneNode(true);


                /*
                | Reset Values
                */

                newRow.querySelectorAll('input').forEach(function(input) {

                    if (input.classList.contains('quantity')) {

                        input.value = '1';

                    } else if (
                        input.classList.contains('unit-cost')
                    ) {

                        input.value = '0';

                    } else if (
                        input.classList.contains('line-total')
                    ) {

                        input.value = '0.00';

                    }

                });


                /*
                | Reset Product
                */

                newRow.querySelector('.product-select').value =
                    '';


                /*
                | Update Array Index
                */

                newRow.querySelectorAll(
                    'input, select'
                ).forEach(function(element) {

                    const name =
                        element.getAttribute('name');


                    if (name) {

                        element.setAttribute(
                            'name',
                            name.replace(
                                /\[\d+\]/,
                                '[' + rowIndex + ']'
                            )
                        );

                    }

                });


                rowIndex++;


                /*
                | Add Row
                */

                itemsContainer.appendChild(newRow);


                /*
                | Bind Events
                */

                bindRow(newRow);


                /*
                | Recalculate
                */

                calculateTotals();

            });


            /*
            |--------------------------------------------------------------------------
            | Discount
            |--------------------------------------------------------------------------
            */

            document.getElementById('discount')
                .addEventListener(
                    'input',
                    calculateTotals
                );


            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */

            document.getElementById('tax')
                .addEventListener(
                    'input',
                    calculateTotals
                );


            /*
            |--------------------------------------------------------------------------
            | Paid Amount
            |--------------------------------------------------------------------------
            */

            document.getElementById('paid_amount')
                .addEventListener(
                    'input',
                    calculateTotals
                );


            /*
            |--------------------------------------------------------------------------
            | Initial Calculation
            |--------------------------------------------------------------------------
            */

            calculateTotals();


            /*
            |--------------------------------------------------------------------------
            | Auto Hide Messages
            |--------------------------------------------------------------------------
            */

            setTimeout(function() {

                const successMessage =
                    document.getElementById('success-message');

                if (successMessage) {
                    successMessage.remove();
                }


                const errorMessage =
                    document.getElementById('error-message');

                if (errorMessage) {
                    errorMessage.remove();
                }


                const validationErrors =
                    document.getElementById('validation-errors');

                if (validationErrors) {
                    validationErrors.remove();
                }

            }, 5000);

        });
    </script>

@endsection
