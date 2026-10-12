<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Purchase;
use App\Models\SupplierPaymentAllocation;





class SupplierPaymentController extends Controller
{
    public function index(Supplier $supplier): View
    {
        $payments = $supplier->payments()
            ->with('user')
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15);

        return view(
            'supplier_payments.index',
            compact('supplier', 'payments')
        );
    }

    public function create(Supplier $supplier): View
    {
        return view(
            'supplier_payments.create',
            compact('supplier')
        );
    }

    public function store(
        Request $request,
        Supplier $supplier
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'payment_method' => [
                'required',
                'in:cash,bank,cheque,online',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'allocations' => [
                'nullable',
                'array',
            ],

            'allocations.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $supplier
        ) {
            /*
         * Lock the supplier so two users cannot
         * process supplier payments simultaneously.
         */
            $lockedSupplier = Supplier::whereKey($supplier->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
         * Create the supplier payment first.
         */
            $payment = SupplierPayment::create([
                'supplier_id' => $lockedSupplier->id,
                'user_id' => auth()->id(),
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' =>
                $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAllocated = 0;

            /*
         * Process selected allocations.
         */
            foreach ($validated['allocations'] ?? [] as $purchaseId => $amount) {

                $amount = (float) $amount;

                /*
             * Ignore empty allocation fields.
             */
                if ($amount <= 0) {
                    continue;
                }

                /*
             * Lock the purchase while checking
             * its outstanding balance.
             */
                $purchase = Purchase::whereKey($purchaseId)
                    ->where('supplier_id', $lockedSupplier->id)
                    ->where('status', 'posted')
                    ->lockForUpdate()
                    ->first();

                if (! $purchase) {
                    throw ValidationException::withMessages([
                        'allocations' =>
                        "Invalid purchase selected for allocation.",
                    ]);
                }

                /*
             * Calculate actual amount already allocated
             * directly from the database.
             */
                $alreadyAllocated = (float) $purchase
                    ->paymentAllocations()
                    ->sum('amount');

                $outstanding = round(
                    (float) $purchase->total_amount
                        - $alreadyAllocated,
                    2
                );

                /*
             * Prevent allocating more than the invoice balance.
             */
                if ($amount > $outstanding) {
                    throw ValidationException::withMessages([
                        'allocations' =>
                        "Allocation for invoice {$purchase->invoice_number} "
                            . "cannot exceed its outstanding amount of "
                            . number_format($outstanding, 2) . ".",
                    ]);
                }

                $totalAllocated = round(
                    $totalAllocated + $amount,
                    2
                );

                /*
             * Prevent allocating more than the payment itself.
             */
                if ($totalAllocated > (float) $payment->amount) {
                    throw ValidationException::withMessages([
                        'allocations' =>
                        'Total allocation cannot exceed the payment amount.',
                    ]);
                }

                /*
             * Create the allocation.
             */
                SupplierPaymentAllocation::create([
                    'supplier_payment_id' => $payment->id,
                    'purchase_id' => $purchase->id,
                    'amount' => $amount,
                ]);
            }
        });

        return redirect()
            ->route(
                'suppliers.payments.index',
                $supplier
            )
            ->with(
                'success',
                'Supplier payment recorded successfully.'
            );
    }
}
