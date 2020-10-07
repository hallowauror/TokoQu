<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use \File;
use Image;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('keyword')){
            $products = Product::where('product_name', 'like', '%'.$request->keyword.'%')->get();
        } else {
            $products = Product::with('category')->orderBy('created_at', 'DESC')->paginate();
        }
        
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $this->validate($request, [
            'code' => 'required|string|max:15|unique:products',
            'product_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
            'stock' => 'required|integer',
            'buy_price' => 'required|integer',
            'sell_price' => 'required|integer',
            'weight' => 'required|integer',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg',
            'category_id' => 'required|exists:categories,id_category'
        ]);

        try {
            // Set default product_image to null
            $product_image = null;
             // jika melakukan upload gambar produk
             if ($request->hasFile('product_image')){
                 // jalankan method saveFile()
                 $product_image = $this->saveFile($request->product_name, $request->file('product_image'));
             }

             // simpan data ke dalam table products
             $product = Product::create([
                'code' => $request->code,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'stock' => $request->stock,
                'buy_price' => $request->buy_price,
                'sell_price' => $request->sell_price,
                'weight' => $request->weight,
                'product_image' => $product_image,
                'category_id' => $request->category_id
             ]);

             //jika berhasil direct ke produk.index
            return redirect(route('produk.index'))
            ->with(['success' => '<strong>' . $product->product_name . '</strong> Ditambahkan']);
        } catch (\Exception $e) {
             //jika gagal, kembali ke halaman sebelumnya kemudian tampilkan error
             return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    private function saveFile($product_name, $product_image)
    {
        // nama file merupakan gabungan nama produk dengan time()
        $images = Str::slug($product_name) . time() . '.' . $product_image->getClientOriginalExtension();

        // set path folder untuk menyimpan gambar
        $path = public_path('uploads/product');

        // cek jika uploads/product bukan direktori
        if(!File::isDirectory($path)){
            // maka folder tersebut dibuat
            File::makeDirectory($path, 0777, true, true);
        }

        // simpan gambar yang diupload ke folder uploads/product
        Image::make($product_image)->save($path .  '/' . $images);

        // return nama file yang ada pada variable $images
        return $images;
    }

    public function destroy($id)
    {
        // select berdasarkan id
        $products = Product::findOrFail($id);
        //mengecek, jika field photo kosong
        if (!empty($products->product_image)) {
            //file akan dihapus dari folder uploads/produk
            File::delete(public_path('uploads/product/' . $products->product_image));
        }

        // hapus data
        $products->delete();
        return redirect()->back()->with(['success' => '<strong>' . $products->product_name . '</strong> Telah Dihapus!']);
    }

    public function edit($id)
    {
        // select berdasarkan id
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required|string|max:15|exists:products,code',
            'product_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
            'stock' => 'required|integer',
            'buy_price' => 'required|integer',
            'sell_price' => 'required|integer',
            'weight' => 'required|integer',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg',
            'category_id' => 'required|exists:categories,id_category'
        ]);

        try {
            // select berdasarkan id
            $product = Product::findOrFail($id);
            $product_image = $product->product_image;

            //cek jika ada file yang dikirim dari form
            if ($request->hasFile('product_image')) {
                //cek, jika gambar tidak kosong maka file yang ada di folder uploads/product akan dihapus
                !empty($product_image) ? File::delete(public_path('uploads/product/' . $product_image)):null;
                //uploading file dengan menggunakan method saveFile() yg telah dibuat sebelumnya
                $product_image = $this->saveFile($request->product_name, $request->file('product_image'));
            }

            // update database
            $product->update([
                'code' => $request->code,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'stock' => $request->stock,
                'buy_price' => $request->buy_price,
                'sell_price' => $request->sell_price,
                'weight' => $request->weight,
                'product_image' => $product_image,
                'category_id' => $request->category_id
            ]);

            return redirect(route('produk.index'))->with(['success' => '<strong>' . $product->product_name . '</strong> Diperbaharui']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

}
