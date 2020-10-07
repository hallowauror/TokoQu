@extends('layouts.master')
​
@section('title')
    <title>Setting Profile</title>
@endsection
​
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
​        
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" src='/storage/photos/{{ $user->profile_photo }}' alt="User profile picture" style="width:100px;height:100px;">
                </div>

                <h3 class="profile-username text-center"> {{Auth::user()->name}}</h3>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <strong>Email</strong>
                        <p>
                        {{Auth::user()->email}}
                        </p>
                  </li>
                  <li class="list-group-item">
                    <strong>Role</strong>
                        <p>
                        @foreach(Auth::user()->roles as $role)
                            {{ $role->name }}
                        @endforeach
                        </p>
                  </li>
                </ul>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            @card
                @slot('title')
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Setting Profile</a></li>
                </ul>
                @endslot

                @if (session('success'))
                    @alert(['type' => 'success'])
                        {!! session('success') !!}
                    @endalert
                @endif

                @if (session('error'))
                    @alert(['type' => 'error'])
                        {!! session('error') !!}
                    @endalert
                @endif

                    <form  action="{{ route('setting.updateProfile') }}" method="post" enctype="multipart/form-data">
                        @csrf
                      <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required value="{{ $user->name }}">
                            <p class="text-danger">{{ $errors->first('name') }}</p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" required value="{{ $user->email }}" readonly>
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        </div>
                      </div>
                      <div class="form-group row">
                          <label for="password" class="col-sm-2 col-form-label">Password</label>
                          <div class="col-sm-10">
                              <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid':'' }}">
                              <p class="text-danger">{{ $errors->first('password') }}</p>
                              <p class="text-warning">Biarkan kosong, jika tidak ingin mengganti password</p>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="role" class="col-sm-2 col-form-label">Role</label>
                        <div class="col-sm-10"> 
                            @foreach(Auth::user()->roles as $role)
                            <input type="text" name="role" required
                                        value="{{ $role->name }}"
                                        readonly
                                        class="form-control {{ $errors->has('role') ? 'is-invalid':'' }}">
                            <p class="text-danger">{{ $errors->first('role') }}</p>
                            @endforeach
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="profile_photo" class="col-sm-2 col-form-label">Foto Profile</label>
                        <div class="col-sm-offset-2 col-sm-5">
                            <input type="file" name="profile_photo" class="form-input"> 
                            <p class="text-danger"> {{ $errors->first('profile_photo') }}</p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            <button class="btn btn-info btn-sm">
                                <i class="fa fa-refresh"></i> Update
                            </button>
                        </div>
                      </div>
                    </form>

            </div>
            <!-- /.nav-tabs-custom -->
                @slot('footer')

                @endslot
            @endcard
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

    </div>
@endsection
