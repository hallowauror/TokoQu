@extends('layouts.master')
​
@section('title')
    <title>Tambah Data Produk</title>
@endsection
​
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Tambah Data Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
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

                            @if (session('success'))
                                @alert(['type' => 'success'])
                                    {!! session('success') !!}
                                @endalert
                            @endif
                            <form action="{{ route('produk.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Kode Produk</label>
                                    <input type="text" name="code" required
                                        maxlength="10"
                                        class="form-control {{ $errors->has('code') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('code') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Produk</label>
                                    <input type="text" name="product_name" required
                                        class="form-control {{ $errors->has('product_name') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('product_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Deskripsi</label>
                                    <textarea name="description" id="description"
                                        cols="5" rows="5"
                                        class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}"></textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Stok</label>
                                    <input type="number" name="stock" required
                                        class="form-control {{ $errors->has('stock') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Harga Beli</label>
                                    <input type="number" name="buy_price" required
                                        class="form-control {{ $errors->has('buy_price') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('buy_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Harga Jual</label>
                                    <input type="number" name="sell_price" required
                                        class="form-control {{ $errors->has('sell_price') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('sell_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Berat (gr)</label>
                                    <input type="number" name="weight" required
                                        class="form-control {{ $errors->has('weight') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('weight') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="category_id" id="category_id"
                                        required class="form-control {{ $errors->has('category_id') ? 'is-invalid':'' }}">
                                        <option value="">Pilih</option>
                                        @foreach ($categories as $ct)
                                            <option value="{{ $ct->id_category }}">{{ ucfirst($ct->name) }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <input type="file" name="product_image" class="form-control">
                                    <p class="text-danger">{{ $errors->first('product_image') }}</p>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm">
                                        Simpan
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
