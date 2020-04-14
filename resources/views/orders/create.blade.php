@extends('layouts.master')
​
@section('title')
    <title>Tambah Order</title>
@endsection
​
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Tambah Data Order</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></li>
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

                            @if (session('error'))
                                @alert(['type' => 'bg-danger'])
                                    {!! session('error') !!}
                                @endalert
                            @endif
                            <form action="{{ route('order.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Invoice</label>
                                    <input type="text" name="invoice" required
                                        maxlength="10"
                                        class="form-control {{ $errors->has('invoice') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('invoice') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Customer</label>
                                    <select name="customer_id" id="customer_id"
                                        required class="form-control {{ $errors->has('customer_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($customers as $cust)
                                            <option value="{{ $cust->id_customer }}">{{ ucfirst($cust->name_customer) }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Petugas</label>
                                    <select name="user_id" id="user_id"
                                        required class="form-control {{ $errors->has('user_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ ucfirst($user->name) }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('user_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Produk</label>
                                    <select name="product_id" id="product_id"
                                        required class="form-control {{ $errors->has('product_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id_product }}">{{ ucfirst($product->product_name) }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('product_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Qty</label>
                                    <input type="number" name="qty" required
                                        class="form-control {{ $errors->has('qty') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('qty') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Total</label>
                                    <input type="number" name="total" required
                                        class="form-control {{ $errors->has('total') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('total') }}</p>
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
