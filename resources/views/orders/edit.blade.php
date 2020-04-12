@extends('layouts.master')
​
@section('title')
    <title>Edit Data Order</title>
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
                            <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></li>
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
                            <form action="{{ route('order.update', $order->id_order) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="">Invoice</label>
                                    <input type="text" name="invoice" required
                                        value="{{ $order->invoice }}"
                                        readonly
                                        class="form-control {{ $errors->has('invoice') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('invoice') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Customer</label>
                                    <select name="customer_id" id="customer_id"
                                        required class="form-control {{ $errors->has('customer_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($customers as $row)
                                            <option value="{{ $row->id_customer }}" {{ $row->id_customer == $order->customer_id ? 'selected':'' }}>
                                                {{ ucfirst($row->name_customer) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Petugas</label>
                                    <select name="user_id" id="user_id"
                                        required class="form-control {{ $errors->has('user_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($users as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == $order->user_id ? 'selected':'' }}>
                                                {{ ucfirst($row->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Total</label>
                                    <input type="number" name="total" required
                                        value="{{ $order->total }}"
                                        class="form-control {{ $errors->has('total') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('total') }}</p>
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
