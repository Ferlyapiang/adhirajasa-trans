<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LogData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('master-data.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('master-data.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_npwp_ktp' => 'required|string|unique:customers',
            'no_hp' => 'required|string',
            'email' => 'required|string|email|unique:customers',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $customer = Customer::create($request->all());

        // Log the create action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created customer ID: ' . $customer->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('master-data.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('master-data.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('master-data.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_npwp_ktp' => 'required|string|unique:customers,no_npwp_ktp,' . $customer->id,
            'no_hp' => 'required|string',
            'email' => 'required|string|email|unique:customers,email,' . $customer->id,
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($request->all());

        // Log the update action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'update',
            'details' => 'Updated customer ID: ' . $customer->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('master-data.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        // Soft delete, assuming you have soft deletes enabled
        $customer->delete();

        // Log the delete action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'delete',
            'details' => 'Deleted customer ID: ' . $customer->id . ' with data: ' . json_encode($customer->only('name', 'no_npwp_ktp', 'no_hp', 'email', 'address', 'status'))
        ]);

        return redirect()->route('master-data.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
