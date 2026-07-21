<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Constants\Messages;
use App\Models\ActivityLog;
use App\Constants\ActivityLogColumns;

class CustomerController extends Controller
{
    /** Display a listing of the resource. */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $customers = Customer::getCustomerAll($search, $status);

        return view('customer.index', compact('customers', 'search', 'status'));
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        ActivityLog::logActivity(
            ActivityLogColumns::ACTION_CREATE,
            ActivityLogColumns::MODULE_CUSTOMER,
            "Menambahkan Customer '{$customer->customer_name}'",
            $customer->id
        );

        return redirect()->route('customers.index')->with('success', Messages::CUSTOMER_CREATED);
    }

    /** Show the form for editing the specified resource. */
    public function edit($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return abort(404, Messages::CUSTOMER_NOT_FOUND);
        }
        return view('customer.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->back()->with('error', Messages::CUSTOMER_NOT_FOUND);
        }

        $customer->update($request->validated());

        ActivityLog::logActivity(
            ActivityLogColumns::ACTION_UPDATE,
            ActivityLogColumns::MODULE_CUSTOMER,
            "Memperbarui Customer '{$customer->customer_name}'",
            $customer->id
        );

        return redirect()->route('customers.index')->with('success', Messages::CUSTOMER_UPDATED);
    }

    /** Display the specified resource. */
    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return abort(404, Messages::CUSTOMER_NOT_FOUND);
        }
        return view('customer.detail', compact('customer'));
    }

    /** Remove the specified resource from storage. */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customers.index')->with('error', Messages::CUSTOMER_NOT_FOUND);
        }

        $customerName = $customer->customer_name;
        $customer->delete();

        ActivityLog::logActivity(
            ActivityLogColumns::ACTION_DELETE,
            ActivityLogColumns::MODULE_CUSTOMER,
            "Menghapus Customer '{$customerName}'",
            $id
        );

        return redirect()->route('customers.index')->with('success', Messages::CUSTOMER_DELETED);
    }
}
