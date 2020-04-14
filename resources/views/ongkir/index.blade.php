@extends('layouts.master')
​
@section('title')
    <title>Cek Ongkir</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
​
@section('content')
<div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Cek Ongkir</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Cek Ongkir</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Asal</h3>
                        <hr>
                        <div class="form-group">
                            <label class="font-weight-bold">PROVINSI ASAL</label>
                            <select class="form-control provinsi-asal" name="province_origin">
                                <option value="0">-- Pilih provinsi asal --</option>
                                @foreach ($provinces as $province => $value)
                                    <option value="{{ $province  }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">KOTA / KABUPATEN ASAL</label>
                            <select class="form-control kota-asal" name="city_origin">
                                <option value="">-- Pilih kota asal --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Tujuan</h3>
                        <hr>
                        <div class="form-group">
                            <label class="font-weight-bold">PROVINSI TUJUAN</label>
                            <select class="form-control provinsi-tujuan" name="province_destination">
                                <option value="0">-- Pilih provinsi tujuan --</option>
                                @foreach ($provinces as $province => $value)
                                    <option value="{{ $province  }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">KOTA / KABUPATEN TUJUAN</label>
                            <select class="form-control kota-tujuan" name="city_destination">
                                <option value="">-- Pilih kota tujuan --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Kurir</h3>
                        <hr>
                        <div class="form-group">
                            <label>PROVINSI TUJUAN</label>
                            <select class="form-control kurir" name="courier">
                                <option value="0">-- Pilih kurir --</option>
                                <option value="jne">JNE</option>
                                <option value="pos">POS</option>
                                <option value="tiki">TIKI</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">BERAT (gram)</label>
                            <input type="number" class="form-control" name="weight" id="weight" placeholder="Masukkan Berat (gram)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-md btn-primary btn-block btn-check">CEK ONGKOS KIRIM</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card d-none ongkir">
                    <div class="card-body">
                        <ul class="list-group" id="ongkir"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        //active select2
        $(".provinsi-asal , .kota-asal, .provinsi-tujuan, .kota-tujuan").select2({
            theme:'bootstrap4',width:'style',
        });
        //ajax select kota asal
        $('select[name="province_origin"]').on('change', function () {
            let provindeId = $(this).val();
            if (provindeId) {
                jQuery.ajax({
                    url: '/cities/'+provindeId,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $('select[name="city_origin"]').empty();
                        $('select[name="city_origin"]').append('<option value="">-- pilih kota asal --</option>');
                        $.each(response, function (key, value) {
                            $('select[name="city_origin"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                $('select[name="city_origin"]').append('<option value="">-- pilih kota asal --</option>');
            }
        });
        //ajax select kota tujuan
        $('select[name="province_destination"]').on('change', function () {
            let provindeId = $(this).val();
            if (provindeId) {
                jQuery.ajax({
                    url: '/cities/'+provindeId,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $('select[name="city_destination"]').empty();
                        $('select[name="city_destination"]').append('<option value="">-- pilih kota tujuan --</option>');
                        $.each(response, function (key, value) {
                            $('select[name="city_destination"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                $('select[name="city_destination"]').append('<option value="">-- pilih kota tujuan --</option>');
            }
        });
        //ajax check ongkir
        let isProcessing = false;
        $('.btn-check').click(function (e) {
            e.preventDefault();

            let token            = $("meta[name='csrf-token']").attr("content");
            let city_origin      = $('select[name=city_origin]').val();
            let city_destination = $('select[name=city_destination]').val();
            let courier          = $('select[name=courier]').val();
            let weight           = $('#weight').val();

            if(isProcessing){
                return;
            }

            isProcessing = true;
            jQuery.ajax({
                url: "/ongkir",
                data: {
                    _token:              token,
                    city_origin:         city_origin,
                    city_destination:    city_destination,
                    courier:             courier,
                    weight:              weight,
                },
                dataType: "JSON",
                type: "POST",
                success: function (response) {
                    isProcessing = false;
                    if (response) {
                        $('#ongkir').empty();
                        $('.ongkir').addClass('d-block');
                        $.each(response[0]['costs'], function (key, value) {
                            $('#ongkir').append('<li class="list-group-item">'+response[0].code.toUpperCase()+' : <strong>'+value.service+'</strong> - Rp. '+value.cost[0].value+' ('+value.cost[0].etd+' hari)</li>')
                        });

                    }
                }
            });

        });

    });
</script>
@endsection