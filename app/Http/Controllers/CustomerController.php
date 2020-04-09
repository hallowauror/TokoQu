<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function search(Request $request)
    {
        $this->validate($request, [
            'email_customer' => 'required|email'
        ]);

        $customer = Customer::where('email_customer', $request->email_customer)->first();

        if($customer){
            return response()->json([
                'status' => 'successfully',
                'data' => $customer
            ], 200);
        }
            return response()->json([
                'status' => 'failed',
                'data' => []
            ]);
    }
}
