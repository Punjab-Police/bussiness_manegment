<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? now()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? now()->toDateString();

        $salesQuery = Sale::where('status', 'posted')
            ->whereBetween('sale_date', [$from, $to]);

        $purchasesQuery = Purchase::where('status', 'posted')
            ->whereBetween('purchase_date', [$from, $to]);

        $expensesQuery = Expense::where('status', 'posted')
            ->whereBetween('expense_date', [$from, $to]);

        $totalSales = (float) (clone $salesQuery)->sum('total_amount');
        $totalPurchases = (float) (clone $purchasesQuery)->sum('total_amount');
        $totalExpenses = (float) (clone $expensesQuery)->sum('amount');

        $receivables = Sale::where('status', 'posted')
            ->withSum('paymentAllocations', 'amount')
            ->get()
            ->sum(fn ($sale) => max(
                0,
                (float) $sale->total_amount
                - (float) ($sale->payment_allocations_sum_amount ?? 0)
            ));

        $payables = Purchase::where('status', 'posted')
            ->withSum('paymentAllocations', 'amount')
            ->get()
            ->sum(fn ($purchase) => max(
                0,
                (float) $purchase->total_amount
                - (float) ($purchase->payment_allocations_sum_amount ?? 0)
            ));

        $accountBalances = Account::where('status', 'active')
            ->withSum([
                'transactions as deposits_total' => fn ($query) =>
                    $query->where('type', 'deposit')
                        ->where('status', 'posted'),
            ], 'amount')
            ->withSum([
                'transactions as withdrawals_total' => fn ($query) =>
                    $query->where('type', 'withdrawal')
                        ->where('status', 'posted'),
            ], 'amount')
            ->get()
            ->sum(fn ($account) =>
                (float) $account->opening_balance
                + (float) ($account->deposits_total ?? 0)
                - (float) ($account->withdrawals_total ?? 0)
            );

        return view('reports.index', compact(
            'from',
            'to',
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'receivables',
            'payables',
            'accountBalances'
        ));
    }
}
