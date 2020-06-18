@extends('layouts.master')
​
@section('title')
    <title>Edit Data Produk</title>
@endsection
​
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Edit Data</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
​
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @card
                            @slot('title')

                            @endslot

                            @if (session('error'))
                                @alert(['type' => 'danger'])
                                    {!! session('error') !!}
                                @endalert
                            @endif
                            <form action="{{ route('produk.update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="">Kode Produk</label>
                                    <input type="text" name="code" required
                                        maxlength="10"
                                        readonly
                                        value="{{ $product->code }}"
                                        class="form-control {{ $errors->has('code') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('code') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Nama Produk</label>
                                    <input type="text" name="product_name" required
                                        value="{{ $product->product_name }}"
                                        class="form-control {{ $errors->has('product_name') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('product_name') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Deskripsi</label>
                                    <textarea name="description" id="description"
                                        cols="5" rows="5"
                                        class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}">{{ $product->description }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Stok</label>
                                    <input type="number" name="stock" required
                                        value="{{ $product->stock }}"
                                        class="form-control {{ $errors->has('stock') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Harga Beli</label>
                                    <input type="number" name="buy_price" required
                                        value="{{ $product->buy_price }}"
                                        class="form-control {{ $errors->has('buy_price') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('buy_price') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Harga Jual</label>
                                    <input type="number" name="sell_price" required
                                        value="{{ $product->sell_price }}"
                                        class="form-control {{ $errors->has('sell_price') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('sell_price') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Berat (gr)</label>
                                    <input type="number" name="weight" required
                                        value="{{ $product->weight}}"
                                        class="form-control {{ $errors->has('weight') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('weight') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="category_id" id="category_id"
                                        required class="form-control {{ $errors->has('category_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($categories as $row)
                                            <option value="{{ $row->id_category }}" {{ $row->id_category == $product->category_id ? 'selected':'' }}>
                                                {{ ucfirst($row->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category_id') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <input type="file" name="photo" class="form-control">
                                    <p class="text-danger">{{ $errors->first('photo') }}</p>
                                    @if (!empty($product->photo))
                                        <hr>
                                        <img src="{{ asset('uploads/product/' . $product->photo) }}"
                                            alt="{{ $product->product_name }}"
                                            width="150px" height="150px">
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <button class="btn btn-info btn-sm">
                                        <i class="fa fa-refresh"></i> Update
                                    </button>
                                </div>
                            </form>
                            @slot('footer')
​
                            @endslot
                        @endcard
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
