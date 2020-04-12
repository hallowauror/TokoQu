<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Type;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('type')->orderBy('created_at', 'DESC')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $types = Type::orderBy('type_name', 'ASC')->get();
        return view('customers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_customer' => 'required|string|max:100',
            'phone_customer' => 'required|string',
            'email_customer' => 'required|email|unique:customers',
            'type_id' => 'required|exists:types,id_type',
            'address_customer' => 'nullable|string|max:200'
        ]);

        try {
            $customer = Customer::create([
                'name_customer' => $request->name_customer,
                'phone_customer' => $request->phone_customer,
                'email_customer' => $request->email_customer,
                'type_id' => $request->type_id,
                'address_customer' => $request->address_customer
            ]);

            return redirect(route('customer.index'))
            ->with(['success' => '<strong>'. "Data berhasil ditambahkan" .'</strong>']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $types = Type::orderBy('type_name', 'ASC')->get();
        return view('customers.edit', compact('customer', 'types'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name_customer' => 'required|string|max:100',
            'phone_customer' => 'required|string',
            'email_customer' => 'required|email|exists:customers,email_customer',
            'type_id' => 'required|exists:types,id_type',
            'address_customer' => 'nullable|string|max:200'
        ]);

        try {
            $customer = Customer::findOrFail($id);

            $customer->update([
               'name_customer' => $request->name_customer,
               'phone_customer' => $request->phone_customer,
               'email_customer' => $request->email_customer,
               'type_id' => $request->type_id,
               'address_customer' => $request->address_customer
            ]);

            return redirect(route('customer.index'))
            ->with(['success' => '<strong>'. "Data berhasil diperbaharui" .'</strong>']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $customers = Customer::findOrFail($id);

        $customers->delete();
        return redirect()->back()->with(['success' => '<strong>Data Berhasil Dihapus!</strong>']);

    }

    // public function search(Request $request)
    // {
    //     $this->validate($request, [
    //         'email_customer' => 'required|email'
    //     ]);

    //     $customer = Customer::where('email_customer', $request->email_customer)->first();

    //     if($customer){
    //         return response()->json([
    //             'status' => 'successfully',
    //             'data' => $customer
    //         ], 200);
    //     }
    //         return response()->json([
    //             'status' => 'failed',
    //             'data' => []
    //         ]);
    // }
}
