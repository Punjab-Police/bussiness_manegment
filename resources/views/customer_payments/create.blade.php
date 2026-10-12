
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Receive Customer Payment</h1>
            <p class="text-gray-600">
                Customer: {{ $customer->name }}
            </p>
        </div>
        <a href="{{ route('customers.payments.index', $customer) }}"
           class="text-blue-600 hover:underline">
            Payment History
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customers.payments.store', $customer) }}"
          class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block font-medium mb-1">Payment Date</label>
                <input type="date" name="payment_date"
                       value="{{ old('payment_date', now()->toDateString()) }}"
                       required class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Total Received (PKR)</label>
                <input type="number" id="payment_amount" name="amount"
                       min="0.01" step="0.01" value="{{ old('amount') }}"
                       required class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Payment Method</label>
                <select name="payment_method" required
                        class="w-full border rounded p-2">
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Reference / Transaction No.</label>
                <input name="reference_number" maxlength="100"
                       value="{{ old('reference_number') }}"
                       class="w-full border rounded p-2">
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Allocate to Sales Invoices</h2>
            <p class="text-sm text-gray-600 mb-3">
                You may leave the allocation blank to keep part or all of the
                received money as unallocated customer credit.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Invoice</th>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-right">Invoice Total</th>
                            <th class="p-3 text-right">Outstanding</th>
                            <th class="p-3 text-right">Allocate (PKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($invoices as $invoice)
                        @php
                            $due = max(
                                0,
                                (float) $invoice->total_amount
                                - (float) ($invoice->payment_allocations_sum_amount ?? 0)
                            );
                        @endphp
                        <tr class="border-t">
                            <td class="p-3">
                                {{ $invoice->invoice_number }}
                                <input type="hidden"
                                       name="allocations[{{ $loop->index }}][sale_id]"
                                       value="{{ $invoice->id }}">
                            </td>
                            <td class="p-3">{{ $invoice->sale_date->format('d-m-Y') }}</td>
                            <td class="p-3 text-right">
                                {{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td class="p-3 text-right">
                                {{ number_format($due, 2) }}
                            </td>
                            <td class="p-3">
                                <input type="number"
                                       name="allocations[{{ $loop->index }}][amount]"
                                       min="0" max="{{ $due }}" step="0.01"
                                       class="allocation-input w-36 border rounded p-2 text-right"
                                       data-due="{{ $due }}"
                                       value="{{ old('allocations.'.$loop->index.'.amount') }}">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No outstanding sales invoices for this customer.
                                You can still record an advance payment.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-right space-y-1">
                <p>Allocated: <strong>PKR <span id="allocated-total">0.00</span></strong></p>
                <p>Unallocated credit: <strong>PKR <span id="unallocated-total">0.00</span></strong></p>
            </div>
        </div>

        <div>
            <label class="block font-medium mb-1">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full border rounded p-2">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800">
                Save Payment
            </button>
            <a href="{{ route('customers.payments.index', $customer) }}"
               class="border px-5 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>

<script>
function updateAllocationTotals() {
    const payment = Number(document.getElementById('payment_amount').value || 0);
    let allocated = 0;

    document.querySelectorAll('.allocation-input').forEach(input => {
        const value = Number(input.value || 0);
        const due = Number(input.dataset.due || 0);

        input.setCustomValidity(
            value < 0 || value > due
                ? 'Allocation cannot exceed the invoice outstanding balance.'
                : ''
        );

        allocated += value;
    });

    document.getElementById('allocated-total').textContent = allocated.toFixed(2);
    document.getElementById('unallocated-total').textContent =
        Math.max(0, payment - allocated).toFixed(2);
}

document.getElementById('payment_amount').addEventListener('input', updateAllocationTotals);
document.querySelectorAll('.allocation-input').forEach(input => {
    input.addEventListener('input', updateAllocationTotals);
});
updateAllocationTotals();
</script>
@endsection
