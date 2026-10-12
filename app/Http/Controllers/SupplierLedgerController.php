<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\View\View;

class SupplierLedgerController extends Controller
{
    public function show(Supplier $supplier): View
    {
        $purchases = $supplier->purchases()
            ->where('status', 'posted')
            ->withSum('paymentAllocations', 'amount')
            ->orderBy('purchase_date')
            ->orderBy('id')
            ->get();

        $payments = $supplier->payments()
            ->with('user')
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        $entries = collect();

        /*
         * Opening balance
         */
        if ((float) $supplier->opening_balance > 0) {
            $entries->push([
                'date' => null,
                'type' => 'Opening Balance',
                'reference' => '-',
                'description' => 'Opening supplier balance',
                'debit' => (float) $supplier->opening_balance,
                'credit' => 0,
            ]);
        }

        /*
         * Purchases
         */
        foreach ($purchases as $purchase) {
            $entries->push([
                'date' => $purchase->purchase_date,
                'type' => 'Purchase',
                'reference' => $purchase->invoice_number,
                'description' => 'Purchase invoice',
                'debit' => (float) $purchase->total_amount,
                'credit' => 0,
            ]);
        }

        /*
         * Payments
         */
        foreach ($payments as $payment) {
            $entries->push([
                'date' => $payment->payment_date,
                'type' => 'Payment',
                'reference' => $payment->reference_number
                    ?: 'PAY-' . $payment->id,
                'description' => ucfirst($payment->payment_method)
                    . ' payment',
                'debit' => 0,
                'credit' => (float) $payment->amount,
            ]);
        }

        /*
         * Sort all entries chronologically.
         */
        $entries = $entries
            ->sortBy(function ($entry) {
                return [
                    $entry['date']?->format('Y-m-d') ?? '0000-00-00',
                    $entry['reference'],
                ];
            })
            ->values();

        /*
         * Calculate running balance.
         */
        $balance = 0;

        $entries = $entries->map(function ($entry) use (&$balance) {
            $balance = round(
                $balance
                + $entry['debit']
                - $entry['credit'],
                2
            );

            $entry['balance'] = $balance;

            return $entry;
        });

        return view(
            'supplier_ledger.show',
            compact(
                'supplier',
                'entries',
                'balance'
            )
        );
    }
}