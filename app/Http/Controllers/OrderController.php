<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Customer;
use App\User;
use App\Product;
use Cookie;
use DB;
use Carbon\Carbon;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::orderBy('created_at', 'DESC')->with('order_detail', 'customer');
        $customers = Customer::orderBy('name_customer', 'ASC')->get();
        $users = User::orderBy('name', 'ASC')->get();
    
        // Jika customer dipilih
        if(!empty($request->customer_id)){
            $orders = $orders->where('customer_id', $request->customer_id);
        }
    
        // Jika user dipilih
        if(!empty($request->user_id)){
            $orders = $orders->where('user_id', $request->user_id);
        }
    
        // Jika range date terisi
        if(!empty($request->start_date) && !empty($request->end_date)){
            // Validasi format harus date
            $this->validate($request, [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date'
            ]);
    
            // Re-formart range date menjadi Yy-Mm-Dd 
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
    
            // Tambahkan whereBetween condition untuk ambil data dengan range
            $orders = $orders->whereBetween('created_at', [$start_date. ' 00:00:00', $end_date.' 23:59:59'])->get();
        } else {
            // Range date kosong, load 10 data terbaru
            $orders = $orders->take(10)->skip(0)->get();
        }
    
        return view('orders.index', [
            'orders' => $orders,
            'customers' => $customers,
            'users' => $users,
            'sold' => $this->countItem($orders),
            'total_customer' => $this->countCustomer($orders),
            'total' => $this->countTotal($orders)
        ]);
    }
    
    private function countItem($order)
    {
        // Default data 0
        $data = 0;
        // Jika data tersedia
        if($order->count() > 0){
            // Looping
            foreach($order as $row){
                // Ambil qty
                $qty = $row->order_detail->pluck('qty')->all();
                // Jumlahkan qty
                $ttl = array_sum($qty);
                $data += $ttl;
            }
        }
        return $data;
    }

    private function countCustomer($orders)
    {
        // Definisikan array kosong
        $customer = [];
        // Jika data tersedia
        if($orders->count() > 0){
            // Looping untuk simpan email ke dalam array
            foreach($orders as $row){
                $customer[] = $row->customer->email_customer;
            }
        }
        // Hitung total data yang ada dalam array, data yang double (duplicate) akan dihapus dengan array_unique
        return count(array_unique($customer));
    }

    private function countTotal($orders)
    {
        // Default data 0
        $total = 0;
        // Jika data tersedia
        if($orders->count() >0){
            // Ambil value dari total dan gunakan pluck() untuk mengubah ke array
            $sub_total = $orders->pluck('total')->all();
            // Jumlahkan data dalam array
            $total = array_sum($sub_total);
        }
        return $total;
    }
    


    // public function create()
    // {
        //     $customers = Customer::orderBy('name_customer', 'ASC')->get();
        //     $users = User::orderBy('name', 'ASC')->get();
        //     $products = Product::orderBy('product_name', 'ASC')->get();
        
    //     return view('orders.create', compact('customers', 'users', 'products'));
    // }

    // public function store(Request $request)
    // {
    //     $this->validate($request, [
        //         'invoice' => 'required|max:20|unique:orders',
        //         'customer_id' => 'required|exists:customers,id_customer',
    //         'user_id' => 'required|exists:users,id',
    //         'product_id' => 'required|exists:products,id_product',
    //         'qty' => 'required|integer',
    //         'total' => 'required|integer'
    //     ]);
    
    //     try {
    //         $order = Order::create([
    //             'invoice' => $request->invoice,
    //             'customer_id' => $request->customer_id,
    //             'user_id' => $request->user_id,
    //             'product_id' => $request->product_id,
    //             'qty' => $request->qty,
    //             'total' => $request->total
    //         ]);
    
    //         return redirect(route('order.index'))
    //         ->with(['success' => '<strong>'. "Data berhasil ditambahkan" .'</strong>']);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['error' => $e->getMessage()]);
    //    }
    // }

    // public function edit($id)
    // {
    //     $order = Order::findOrFail($id);
    //     $customers = Customer::orderBy('name_customer', 'ASC')->get();
    //     $users = User::orderBy('name', 'ASC')->get();
    //     $products = Product::orderBy('product_name', 'ASC')->get();
    //     return view('orders.edit', compact('order', 'customers', 'users', 'products'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
        //         'invoice' => 'required|max:20|exists:orders,invoice',
    //         'customer_id' => 'required|exists:customers,id_customer',
    //         'user_id' => 'required|exists:users,id',
    //         'product_id' => 'required|exists:products,id_product',
    //         'qty' => 'required|integer',
    //         'total' => 'required|integer'
    //     ]);

    //     try{
        //         $order = Order::findOrFail($id);

    //         $order->update([
    //             'invoice' => $request->invoice,
    //             'customer_id' => $request->customer_id,
    //             'user_id' => $request->user_id,
    //             'product_id' => $request->product_id,
    //             'qty' => $request->qty,
    //             'total' => $request->total
    //         ]);

    //         return redirect(route('order.index'))
    //         ->with(['success' => '<strong>'. "Data berhasil diperbaharui" .'</strong>']);
    //     }  catch (\Exception $e) {
    //             return redirect()->back()->with(['error' => $e->getMessage()]);
    //         }
    // }

    // public function destroy($id)
    // {
    //     $orders = Order::findOrFail($id);

    //     $orders->delete();
    //     return redirect()->back()->with(['success' => '<strong>Data Berhasil Dihapus!</strong>']);

    // }
    
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

        $product = Product::findOrFail($request->product_id);
        // ambil cookie cart dengan request
        $getCart = json_decode($request->cookie('cart'), true);

        //jika datanya ada
        if ($getCart) {
            //jika key nya exists berdasarkan product_id
            if (array_key_exists($request->product_id, $getCart)) {
                //jumlahkan qty barangnya
                $getCart[$request->product_id]['qty'] += $request->qty;
                //dikirim kembali untuk disimpan ke cookie
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120);
           }
        }
        //jika cart kosong, maka tambahkan cart baru
        $getCart[$request->product_id] = [
            'code' => $product->code,
            'name' => $product->product_name,
            'price' => $product->sell_price,
            'qty' => $request->qty
        ];
            //kirim responsenya kemudian simpan ke cookie
            return response()->json($getCart, 200)->cookie('cart', json_encode($getCart), 120);
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
        // Validasi customer
        $this->validate($request, [
            'email_customer' => 'required|email',
            'name_customer' => 'required|string|max:100',
            'phone_customer' => 'required|numeric',
            'address_customer' => 'required'
        ]); 
        
        // ambil list cart dari cookie 
        $cart = json_decode($request->cookie('cart'), true);

        // memanipulasi array untuk menciptakan key baru yaitu result dari hasil perkalian price * qty
        $result = collect($cart)->map(function($value){
            return [
                'code' => $value['code'],
                'name' => $value['name'],
                'qty' => $value['qty'],
                'price' => $value['price'],
                'result' => $value['price'] * $value['qty']
            ];
        })->all();

        DB::beginTransaction();
        try{

            // Simpan data customer
            $customer = Customer::firstOrCreate([
                'email_customer' => $request->email_customer
            ], [
                'name_customer' => $request->name_customer,
                'address_customer' => $request->address_customer,
                'phone_customer' => $request->phone_customer
            ]); 
            
            // Simpan data order
            $order = Order::create([
                'invoice' => $this->generateInvoice(),
                'customer_id' => $customer->id_customer,
                'user_id' => auth()->user()->id,
                'total' => array_sum(array_column($result, 'result'))
            ]); 
            
            // Looping cart untuk order_details
            foreach($result as $key => $row){
                
                $order->order_detail()->create([
                    'product_id' => $key,
                    'qty' => $row['qty'],
                    'price' => $row['price']
                    ]);

            $product = Product::find($key);
            $product->stock = $product->stock - $row['qty'];
            $product->save();
            
            }
            
                Db::commit();
            
            // return status dan message berupa code invoice dan hapus cookie
            return response()->json([
                'status' => 'success',
                'message' => $order->invoice,
            ], 200)->cookie(Cookie::forget('cart'));
        } catch (\Exception $e) {
            // jika error maka di rollback
            Db::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }

    }
    
    public function generateInvoice()
    {
        $order = Order::orderBy('created_at', 'DESC');
        // jika ada record
        if($order->count() > 0){
            // ambil data pertama
            $order = $order->first();
            // explode invoice untuk mendapatkan angka
            $explode = explode('-', $order->invoice);
            $count = $explode[1] + 1;
            // angka dari hasil explode di +1
            return 'INVOICE-'.$count;
        }

        // jika belum ada record maka return invoice-1
        return 'INVOICE-1';
    }

    public function invoicePdf($invoice)
    {
        // Ambil data transaksi berdasarkan invoice
        $order = Order::Where('invoice', $invoice)->with('customer', 'order_detail', 'order_detail.product')->first();
        
        // Set font pdf sans-serif
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])->loadView('orders.invoice', compact('order'));

        return $pdf->stream();
    }

}
