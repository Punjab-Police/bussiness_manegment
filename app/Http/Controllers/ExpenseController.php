<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::with('user')
            ->latest('expense_date')
            ->latest('id')
            ->paginate(15);

        $totalExpenses = Expense::where('status', 'posted')
            ->sum('amount');

        return view('expenses.index', compact(
            'expenses',
            'totalExpenses'
        ));
    }

    public function create(): View
    {
        return view('expenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999999',
            ],
            'expense_date' => ['required', 'date'],
            'payment_method' => [
                'required',
                'in:cash,bank_transfer,cheque,card,other',
            ],
            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['user_id'] = Auth::id();
        $data['expense_number'] = 'EXP-' . now()->format('YmdHis')
            . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        $data['status'] = 'posted';

        Expense::create($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }
}
