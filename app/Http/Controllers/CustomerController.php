<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::latest()
            ->paginate(10);

        return view(
            'customers.index',
            compact('customers')
        );
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with(
                'success',
                'Customer created successfully.'
            );
    }

    public function edit(Customer $customer): View
    {
        return view(
            'customers.edit',
            compact('customer')
        );
    }

    public function update(
        Request $request,
        Customer $customer
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with(
                'success',
                'Customer updated successfully.'
            );
    }

    public function destroy(
        Customer $customer
    ): RedirectResponse {
        if ($customer->sales()->exists()) {
            return back()->with(
                'error',
                'This customer cannot be deleted because sales records exist.'
            );
        }

        if ($customer->payments()->exists()) {
            return back()->with(
                'error',
                'This customer cannot be deleted because payment records exist.'
            );
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with(
                'success',
                'Customer deleted successfully.'
            );
    }
}