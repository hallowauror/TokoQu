@extends('layouts.master')
​
@section('title')
    <title>Produk</title>
@endsection
​
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Produk</li>
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
                            <a href="{{ route('produk.create') }}"
                                class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Tambah
                            </a>
                            @endslot

                            @if (session('success'))
                                @alert(['type' => 'success'])
                                    {!! session('success') !!}
                                @endalert
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Foto Produk</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Stok</th>
                                            <th>Harga Jual</th>
                                            <th>Kategori</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $row)
                                        <tr>
                                            <td>
                                                 @if (!empty($row->product_image))
                                                    <img src="{{ asset('uploads/product/' . $row->product_image) }}"
                                                        alt="{{ $row->product_name}}" width="50px" height="50px">
                                                @else
                                                    <img src="http://via.placeholder.com/50x50" alt="{{ $row->product_name }}">
                                                @endif
                                            </td>
                                            <td>
                                                <p class="text-white bg-success">{{$row->code}}</p>
                                            </td>
                                            <td>
                                                <strong>{{ucfirst($row->product_name)}}</strong>
                                            </td>
                                            <td>{{$row->stock}}</td>
                                            <td>Rp. {{number_format($row->sell_price)}}</td>
                                            <td>{{ $row->category->name }}</td>
                                            <td>
                                                <form action="{{ route('produk.destroy', $row->id_product) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <a href="{{ route('produk.edit', $row->id_product) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-right">
                                {!! $products->links() !!}
                            </div>
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
