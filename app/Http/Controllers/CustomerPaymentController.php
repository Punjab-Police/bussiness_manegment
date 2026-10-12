<?php
namespace App\Http\Controllers;


use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\CustomerPaymentAllocation;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerPaymentController extends Controller
{
    public function index(Customer $customer): View
    {
        $payments = CustomerPayment::where('customer_id', $customer->id)
            ->with('user')
            ->withSum('allocations', 'amount')
            ->latest('payment_date')
            ->paginate(15);

        return view('customer_payments.index', compact(
            'customer',
            'payments'
        ));
    }

    public function create(Customer $customer): View
    {
        $invoices = Sale::where('customer_id', $customer->id)
            ->where('status', 'posted')
            ->withSum('paymentAllocations', 'amount')
            ->orderBy('sale_date')
            ->get()
            ->filter(function ($sale) {
                return (float) $sale->total_amount
                    - (float) ($sale->payment_allocations_sum_amount ?? 0)
                    > 0.009;
            });

        return view('customer_payments.create', compact(
            'customer',
            'invoices'
        ));
    }

    public function store(
        Request $request,
        Customer $customer
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0', 'max:999999999999'],
            'payment_method' => [
                                  'required',
                                  'in:cash,bank_transfer,cheque,card,other',
                                  ],

            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'allocations' => ['nullable', 'array'],
            'allocations.*.sale_id' => ['nullable', 'integer', 'distinct'],
            'allocations.*.amount' => [
                'nullable',
                'numeric',
                'gt:0',
                'max:999999999999'
            ],
        ]);

        $paymentAmount = round((float) $validated['amount'], 2);
        $allocations = $validated['allocations'] ?? [];

        // Ignore rows where no invoice was selected.
        $allocations = collect($allocations)
            ->filter(fn ($row) => !empty($row['sale_id']))
            ->values();

        $allocationTotal = round(
            (float) $allocations->sum(
                fn ($row) => (float) ($row['amount'] ?? 0)
            ),
            2
        );

        if ($allocationTotal > $paymentAmount + 0.009) {
            throw ValidationException::withMessages([
                'amount' => 'Allocated amounts cannot exceed the payment amount.',
            ]);
        }

        DB::transaction(function () use (
            $customer,
            $validated,
            $paymentAmount,
            $allocations
        ) {
            $payment = CustomerPayment::create([
                'customer_id' => $customer->id,
                'user_id' => auth()->id(),
                'payment_date' => $validated['payment_date'],
                'amount' => $paymentAmount,
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($allocations as $row) {
                $amount = round((float) ($row['amount'] ?? 0), 2);

                if ($amount <= 0) {
                    throw ValidationException::withMessages([
                        'allocations' => 'Enter a positive amount for each selected invoice.',
                    ]);
                }

                $sale = Sale::whereKey($row['sale_id'])
                    ->lockForUpdate()
                    ->first();

                if (
                    !$sale ||
                    (int) $sale->customer_id !== (int) $customer->id ||
                    $sale->status !== 'posted'
                ) {
                    throw ValidationException::withMessages([
                        'allocations' => 'An invoice is invalid or does not belong to this customer.',
                    ]);
                }

                $alreadyAllocated = (float) CustomerPaymentAllocation::where(
                    'sale_id',
                    $sale->id
                )->sum('amount');

                $outstanding = round(
                    (float) $sale->total_amount - $alreadyAllocated,
                    2
                );

                if ($amount > $outstanding + 0.009) {
                    throw ValidationException::withMessages([
                        'allocations' => "Allocation exceeds the outstanding balance of invoice {$sale->invoice_number}.",
                    ]);
                }

                CustomerPaymentAllocation::create([
                    'customer_payment_id' => $payment->id,
                    'sale_id' => $sale->id,
                    'amount' => $amount,
                ]);
            }
        });

        return redirect()
            ->route('customers.payments.index', $customer)
            ->with('success', 'Customer payment recorded successfully.');
    }

    public function show(
        Customer $customer,
        CustomerPayment $payment
    ): View {
        abort_unless(
            (int) $payment->customer_id === (int) $customer->id,
            404
        );

        $payment->load([
            'user',
            'allocations.sale',
        ]);

        return view('customer_payments.show', compact(
            'customer',
            'payment'
        ));
    }
}
