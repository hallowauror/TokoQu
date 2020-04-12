@extends('layouts.master')
​
@section('title')
    <title>Edit Data Customer</title>
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
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
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
                            <form action="{{ route('customer.update', $customer->id_customer) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="">Nama Customer</label>
                                    <input type="text" name="name_customer" required
                                        value="{{ $customer->name_customer }}"
                                        class="form-control {{ $errors->has('name_customer') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('name_customer') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">No. Telpon</label>
                                    <input type="number" name="phone_customer" required
                                        value="{{ $customer->phone_customer }}"
                                        class="form-control {{ $errors->has('phone_customer') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('phone_customer') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Email Customer</label>
                                    <input type="text" name="email_customer" required
                                        value="{{ $customer->email_customer }}"
                                        class="form-control {{ $errors->has('email_customer') ? 'is-invalid':'' }}">
                                    <p class="text-danger">{{ $errors->first('email_customer') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Customer</label>
                                    <select name="type_id" id="type_id"
                                        required class="form-control {{ $errors->has('type_id') ? 'is-invalid':'' }}">
                                        <option disabled>Pilih</option>
                                        @foreach ($types as $row)
                                            <option value="{{ $row->id_type }}" {{ $row->id_type == $customer->type_id ? 'selected':'' }}>
                                                {{ ucfirst($row->type_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat Customer</label>
                                    <textarea name="address_customer" id="address_customer"
                                        cols="5" rows="5"
                                        class="form-control {{ $errors->has('address_customer') ? 'is-invalid':'' }}">{{ $customer->address_customer }}</textarea>
                                    <p class="text-danger">{{ $errors->first('address_customer') }}</p>
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
