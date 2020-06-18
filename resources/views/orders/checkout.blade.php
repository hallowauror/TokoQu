@extends('layouts.master')
​
@section('title')
    <title>Checkout</title>
@endsection
​
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
​
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Checkout</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('order.tambah') }}">Transaksi</a></li>
                            <li class="breadcrumb-item active">Checkout</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
​
        <section class="content" id="el">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        @card
                            @slot('title')
                                <h4 class="card-title">Data Customer</h4>
                            @endslot
                                <!-- JIKA VALUE DARI message ada, maka alert success akan ditampilkan -->
                                <div v-if="message" class="alert alert-success">
                                    Transaksi telah disimpan, Invoice: <strong>#@{{ message }}</strong>
                                </div>

                                <div class="form-group">
                                    <label for="">Customer</label>
                                    <input type="text" name="email_customer"
                                        v-model="customer.email_customer"
                                        class="form-control"
                                        @keyup.enter.prevent="searchCustomer" required>

                                        <p>Tekan tombol Enter untuk melakukan cek email</p>
                                </div>

                                <!-- JIKA formCustomer BERNILAI TRUE, MAKA FORM AKAN DITAMPILKAN -->
                                <div v-if="formCustomer">
                                    <div class="form-group">
                                        <label for="">Nama Pelanggan</label>
                                        <input type="text" name="name_customer" 
                                            v-model="customer.name_customer"
                                            :disabled="resultStatus"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Alamat</label>
                                        <textarea name="address_customer"
                                            class="form-control"
                                            :disabled="resultStatus"
                                            v-model="customer.address_customer"
                                            cols="5" rows="5" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">No Telp</label>
                                        <input type="number" name="phone_customer" 
                                            v-model="customer.phone_customer"
                                            :disabled="resultStatus"
                                            class="form-control" required>
                                    </div>
                                </div>

                            @slot('footer')
                                <div class="card-footer text-muted">
                                    <!-- JIKA VALUE DARI errorMessage ada, maka alert danger akan ditampilkan -->
                                    <div v-if="errorMessage" class="alert alert-danger">
                                        @{{ errorMessage }}
                                    </div>
                                    <!-- JIKA TOMBOL DITEKAN MAKA AKAN MEMANGGIL METHOD sendOrder -->
                                    <button class="btn btn-primary btn-sm float-right"
                                        :disabled="submitForm"  
                                        @click.prevent="sendOrder"
                                        >
                                        @{{ submitForm ? 'Loading...':'Tambah Order' }}
                                    </button>
                                </div>
                            @endslot
                        @endcard
                    </div>
                    @include('orders.cart')
                </div>
            </div>
        </section>
    </div>
@endsection
​
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
    <script src="{{ asset('js/transaksi.js') }}"></script>
@endsection