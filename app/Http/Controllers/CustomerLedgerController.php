<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;

class CustomerLedgerController extends Controller
{
    public function show(Customer $customer): View
    {
        $sales = $customer->sales()
            ->where('status', 'posted')
            ->orderBy('sale_date')
            ->orderBy('id')
            ->get();

        $payments = $customer->payments()
            ->with('allocations.sale')
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        $transactions = collect();

        foreach ($sales as $sale) {
            $transactions->push([
                'date' => $sale->sale_date,
                'id' => $sale->id,
                'type' => 'Sale',
                'reference' => $sale->invoice_number,
                'description' => 'Sales invoice',
                'debit' => (float) $sale->total_amount,
                'credit' => 0,
            ]);
        }

        foreach ($payments as $payment) {
            $transactions->push([
                'date' => $payment->payment_date,
                'id' => $payment->id,
                'type' => 'Payment',
                'reference' => $payment->reference_number
                    ?: 'PAY-' . $payment->id,
                'description' => 'Payment received',
                'debit' => 0,
                'credit' => (float) $payment->amount,
            ]);
        }

        $transactions = $transactions
            ->sortBy(fn ($row) => $row['date']->format('Y-m-d')
                . '-' . str_pad($row['id'], 10, '0', STR_PAD_LEFT))
            ->values();

        $openingBalance = (float) $customer->opening_balance;
        $runningBalance = $openingBalance;

        $transactions = $transactions->map(function ($row) use (&$runningBalance) {
            $runningBalance += $row['debit'] - $row['credit'];

            $row['balance'] = $runningBalance;

            return $row;
        });

        $totalSales = $sales->sum('total_amount');
        $totalPayments = $payments->sum('amount');

        return view('customer_ledger.show', compact(
            'customer',
            'transactions',
            'openingBalance',
            'totalSales',
            'totalPayments',
            'runningBalance'
        ));
    }
}
