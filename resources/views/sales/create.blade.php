@extends('layouts.app')

@section('title', 'Create Sales Invoice')

@section('header')
    Create Sales Invoice
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sales.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white border rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4">Invoice Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-medium mb-2">Customer *</label>
                        <select name="customer_id" required class="w-full rounded-lg border-slate-300">
                            <option value="">Select customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Invoice Number *</label>
                        <input name="invoice_number" required value="{{ old('invoice_number') }}"
                            placeholder="e.g. SAL-0001" class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Sale Date *</label>
                        <input type="date" name="sale_date" required
                            value="{{ old('sale_date', now()->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-slate-300">
                    </div>

                </div>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Products</h2>

                    <button type="button" id="add-item"
                        class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        + Add Product
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-3 text-left">Product</th>
                                <th class="p-3 text-right">Available</th>
                                <th class="p-3 text-right">Quantity</th>
                                <th class="p-3 text-right">Unit Price</th>
                                <th class="p-3 text-right">Line Total</th>
                                <th class="p-3"></th>
                            </tr>
                        </thead>
                        <tbody id="sale-items"></tbody>
                    </table>
                </div>

                <template id="sale-row-template">
                    <tr class="border-b sale-row">
                        <td class="p-3 min-w-56">
                            <select name="items[INDEX][product_id]" required
                                class="product-select w-full rounded-lg border-slate-300">
                                <option value="">Select product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->available_stock }}"
                                        data-price="{{ $product->sale_price }}">
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-3 text-right available-stock">-</td>
                        <td class="p-3">
                            <input type="number" name="items[INDEX][quantity]" min="0.001" step="0.001" value="1"
                                required class="quantity-input w-28 rounded-lg border-slate-300 text-right">
                        </td>
                        <td class="p-3">
                            <input type="number" name="items[INDEX][unit_price]" min="0" step="0.01" required
                                class="price-input w-32 rounded-lg border-slate-300 text-right">
                        </td>
                        <td class="p-3 text-right line-total">0.00</td>
                        <td class="p-3 text-right">
                            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                                Remove
                            </button>
                        </td>
                    </tr>
                </template>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4">Invoice Summary</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Discount (Rs.)</label>
                        <input type="number" name="discount" id="discount" min="0" step="0.01"
                            value="{{ old('discount', 0) }}" required class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Tax (Rs.)</label>
                        <input type="number" name="tax" id="tax" min="0" step="0.01"
                            value="{{ old('tax', 0) }}" required class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Notes</label>
                        <input name="notes" value="{{ old('notes') }}" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>

                <div class="mt-6 ml-auto max-w-sm space-y-3">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rs. <strong id="subtotal-display">0.00</strong></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Discount</span>
                        <span>Rs. <strong id="discount-display">0.00</strong></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax</span>
                        <span>Rs. <strong id="tax-display">0.00</strong></span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>Rs. <span id="total-display">0.00</span></span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('sales.index') }}" class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                    Post Sales Invoice
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tbody = document.getElementById('sale-items');
            const template = document.getElementById('sale-row-template');
            const addButton = document.getElementById('add-item');
            let rowIndex = 0;

            const money = value => Number(value || 0).toFixed(2);

            function recalculate() {
                let subtotal = 0;

                tbody.querySelectorAll('.sale-row').forEach(row => {
                    const qty = Number(row.querySelector('.quantity-input').value || 0);
                    const price = Number(row.querySelector('.price-input').value || 0);
                    const total = Math.round(qty * price * 100) / 100;

                    row.querySelector('.line-total').textContent = money(total);
                    subtotal += total;
                });

                const discount = Number(document.getElementById('discount').value || 0);
                const tax = Number(document.getElementById('tax').value || 0);
                const total = subtotal - discount + tax;

                document.getElementById('subtotal-display').textContent = money(subtotal);
                document.getElementById('discount-display').textContent = money(discount);
                document.getElementById('tax-display').textContent = money(tax);
                document.getElementById('total-display').textContent = money(total);
            }

            function addRow() {
                const html = template.innerHTML.replaceAll('INDEX', rowIndex++);
                tbody.insertAdjacentHTML('beforeend', html);

                const row = tbody.lastElementChild;
                const select = row.querySelector('.product-select');
                const priceInput = row.querySelector('.price-input');

                select.addEventListener('change', () => {
                    const option = select.selectedOptions[0];
                    row.querySelector('.available-stock').textContent =
                        option.dataset.stock ?? '-';
                    priceInput.value = option.dataset.price ?? '';
                    recalculate();
                });

                row.querySelectorAll('input').forEach(input => {
                    input.addEventListener('input', recalculate);
                });

                row.querySelector('.remove-item').addEventListener('click', () => {
                    row.remove();
                    recalculate();
                });

                recalculate();
            }

            addButton.addEventListener('click', addRow);
            document.getElementById('discount').addEventListener('input', recalculate);
            document.getElementById('tax').addEventListener('input', recalculate);

            addRow();
        });
    </script>
@endsection
