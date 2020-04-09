<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use Cookie;
use DB;

class OrderController extends Controller
{
    public function addOrder()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('orders.add', compact('products'));
    }

    public function getProduct($id)
    {
        $products = Product::findOrFail($id);
        return response()->json($products, 200);
    }

    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id_product',
            'qty' => 'required|integer'
        ]);

        // ambil data product berdasarkan id
        $product = Product::findOrFail($request->product_id);

        //mengambil cookie cart dengan $request->cookie('cart')
        $getCart = json_decode($request->cookie('cart'), true);

        //jika datanya ada
        if ($getCart) {
            //jika key nya exists berdasarkan product_id
            if (array_key_exists($request->product_id, $getCart)) {
                //jumlahkan qty barangnya
                $getCart[$request->product_id]['qty'] += $request->qty;
                //dikirim kembali untuk disimpan ke cookie
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120); // 120 = lama data tersimpan dalam cookie (satuan menit)
            }
        }

        //jika cart kosong, maka tambahkan cart baru
        $getCart[$request->product_id] = [
            'code' => $product->code,
            'product_name' => $product->product_name,
            'sell_price' => $product->sell_price,
            'qty' => $request->qty
        ];
        //kirim responsenya kemudian simpan ke cookie
        return response()->json($getCart, 200)
            ->cookie('cart', json_encode($getCart), 120);
    }

    public function getCart()
    {
        //mengambil cart dari cookie
        $cart = json_decode(request()->cookie('cart'), true);
        //mengirimkan kembali dalam bentuk json untuk ditampilkan dengan vuejs
        return response()->json($cart, 200);
    }

    public function removeCart($id)
    {
        $cart = json_decode(request()->cookie('cart'), true);
        //menghapus cart berdasarkan product_id
        unset($cart[$id]);
        //cart diperbaharui
        return response()->json($cart, 200)->cookie('cart', json_encode($cart), 120);
    }

    public function checkout()
    {
        return view('orders.checkout');
    }

    public function storeOrder(Request $request)
    {
        $this->validate($request, [
            'email_customer' => 'required|email',
            'name_customer' => 'required|string|max:100',
            'address_customer' => 'required',
            'phone_customer' => 'required|numeric',
            'type' => 'required'
        ]);

        //ambil list dari cookie
        $cart = json_decode($request->cookie('cart'), true);

        //memanipulasi array untuk menciptakan key baru yakni result dari hasil perkalian price * qty
        $result = collect($cart)->map(function($value){
            return [
                'code' => $value['code'],
                'product_name' => $value['product_name'],
                'qty' => $value['qty'],
                'sell_price' => $value['sell_price'],
                'result' => $value['sell_price'] * $value['qty']
            ];
        })->all();

        //transaction
        DB::beginTransaction();
        try {
            //simpan data ke table customers
            $customer = Customer::firstOrCreate([
                'email_customer' => $request->email_customer
            ], [
                'name_customer' => $request->name_customer,
                'address_customer' => $request->address_customer,
                'phone_customer' => $request->phone_customer,
                'type' => $request->type
            ]);

            //simpan data ke table orders
            $order = Order::create([
                'invoice' => $this->generateInvoice(),
                'customer_id' => $customer->id_customer,
                'user_id' => auth()->user()->id,
                'total' => array_sum(array_column($result, 'result')) //array_sum untuk menjumlahkan value dari result
            ]);

            //looping cart untuk disimpan ke table order_detail
            foreach($result as $key => $row){
                $order->orderDetail()->create([
                    'product_id' => $key,
                    'qty' => $row['qty'],
                    'total_price' => $row['total_price']
                ]);
            }

            //apabila tidak terjadi error
            DB::commit();

            //return status dan message code invoice serta hapus cookie
            return response()->json([
                'status' => 'success',
                'message' => $order->invoice,
            ], 200)->cookie(Cookie::forget('cart'));
        } catch (\Exception $e){
            //apabila error, dirollback sehingga tidak terjadi perubahan data
            DB::rollback();
            //return pesan gagal
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function generateInvoice()
    {
        //ambil data dari table orders
        $order = Order::orderBy('created_at', 'DESC');
        //jika ada records
        if($order->count()>0){
            //ambil data pertama yang sudah dishort descending
            $order = $order->first();
            //explode invoice untuk dapatkan angka
            $explode = explode('-', $order->invoice);
            //angka dari hasil explode di +1
            return 'INVOICE - '.$explode[1] + 1;
        }
        //jika belum ada records maka mereturn INVOICE - 1
        return 'INVOICE - 1';
    }

}
