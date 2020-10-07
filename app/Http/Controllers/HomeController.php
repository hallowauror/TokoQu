<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\User;
use App\Customer;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $product = Product::count();
        $order = Order::count();
        $user = User::count();
        $customer = Customer::count();
        return view('home', compact('product', 'order', 'user', 'customer'));
    }

    // Method untuk generate data order 7 hari terakhir
    public function getChart()
    {
        // Ambil tgl 7 hari kebelakang
        $start = Carbon::now()->subWeek()->addDay()->format('Y-m-d') . ' 00:00:01';
        // Ambil tgl hari ini
        $end = Carbon::now()->format('Y-m-d') . ' 23:59:50';

        // Select data kapan ketika record dibuat dan total pesanan
        $order = Order::select(DB::raw('date(created_at) as order_date'), DB::raw('count(*) as total_order'))
            // Dengan kondisi antara tgl variable $start dan $end
            ->whereBetween('created_at', [$start, $end])
            // Kelompokan berdasarkan tgl
            ->groupBy('created_at')
            ->get()->pluck('total_order', 'order_date')->all();

        // Looping tgl dengan interval seminggu terakhir
        for($i = Carbon::now()->subWeek()->addDay(); $i <= Carbon::now(); $i->addDay()){
            // Jika data tersedia
            if (array_key_exists($i->format('Y-m-d'), $order)){
                // Total pesanan di push dengan key tgl
                $data[$i->format('Y-m-d')] = $order[$i->format('Y-m-d')];
            } else {
                // Jika data tidak tersedia, maka masukkan nilai 0
                $data[$i->format('Y-m-d')] = 0;
            }
        }
        return response()->json($data);
    }
}
