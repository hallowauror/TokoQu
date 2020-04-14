<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use App\Customer;
use App\User;
use App\Product;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer', 'product', 'user')->orderBy('created_at', 'DESC')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name_customer', 'ASC')->get();
        $users = User::orderBy('name', 'ASC')->get();
        $products = Product::orderBy('product_name', 'ASC')->get();

        return view('orders.create', compact('customers', 'users', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'invoice' => 'required|max:20|unique:orders',
            'customer_id' => 'required|exists:customers,id_customer',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id_product',
            'qty' => 'required|integer',
            'total' => 'required|integer'
        ]);

        try {
            $order = Order::create([
                'invoice' => $request->invoice,
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'total' => $request->total
            ]);

            return redirect(route('order.index'))
            ->with(['success' => '<strong>'. "Data berhasil ditambahkan" .'</strong>']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
       }
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $customers = Customer::orderBy('name_customer', 'ASC')->get();
        $users = User::orderBy('name', 'ASC')->get();
        $products = Product::orderBy('product_name', 'ASC')->get();
        return view('orders.edit', compact('order', 'customers', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'invoice' => 'required|max:20|exists:orders,invoice',
            'customer_id' => 'required|exists:customers,id_customer',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id_product',
            'qty' => 'required|integer',
            'total' => 'required|integer'
        ]);

        try{
            $order = Order::findOrFail($id);

            $order->update([
                'invoice' => $request->invoice,
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'total' => $request->total
            ]);

            return redirect(route('order.index'))
            ->with(['success' => '<strong>'. "Data berhasil diperbaharui" .'</strong>']);
        }  catch (\Exception $e) {
                return redirect()->back()->with(['error' => $e->getMessage()]);
            }
    }

    public function destroy($id)
    {
        $orders = Order::findOrFail($id);

        $orders->delete();
        return redirect()->back()->with(['success' => '<strong>Data Berhasil Dihapus!</strong>']);

    }

}
