<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = Account::withSum([
            'transactions as deposits_total' => fn ($query) =>
                $query->where('type', 'deposit')->where('status', 'posted'),
        ], 'amount')
        ->withSum([
            'transactions as withdrawals_total' => fn ($query) =>
                $query->where('type', 'withdrawal')->where('status', 'posted'),
        ], 'amount')
        ->orderBy('name')
        ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'type' => ['required', Rule::in(['cash', 'bank'])],
            'opening_balance' => ['required', 'numeric', 'min:0', 'max:999999999999'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['status'] = 'active';

        Account::create($data);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function transactions(Account $account): View
    {
        $transactions = $account->transactions()
            ->with('user')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(20);

        $deposits = (float) $account->transactions()
            ->where('type', 'deposit')
            ->where('status', 'posted')
            ->sum('amount');

        $withdrawals = (float) $account->transactions()
            ->where('type', 'withdrawal')
            ->where('status', 'posted')
            ->sum('amount');

        $balance = (float) $account->opening_balance
            + $deposits - $withdrawals;

        return view('accounts.transactions', compact(
            'account',
            'transactions',
            'deposits',
            'withdrawals',
            'balance'
        ));
    }

    public function createTransaction(Account $account): View
    {
        abort_unless($account->status === 'active', 422, 'This account is inactive.');

        return view('accounts.transaction_create', compact('account'));
    }

    public function storeTransaction(
        Request $request,
        Account $account
    ): RedirectResponse {
        abort_unless($account->status === 'active', 422, 'This account is inactive.');

        $data = $request->validate([
            'transaction_date' => ['required', 'date'],
            'type' => ['required', Rule::in(['deposit', 'withdrawal'])],
            'reference' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999999'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data, $account) {
            Account::whereKey($account->id)->lockForUpdate()->firstOrFail();

            $data['account_id'] = $account->id;
            $data['user_id'] = Auth::id();
            $data['status'] = 'posted';

            AccountTransaction::create($data);
        });

        return redirect()->route('accounts.transactions', $account)
            ->with('success', 'Account transaction recorded successfully.');
    }
}
