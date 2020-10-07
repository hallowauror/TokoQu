<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    // Route yang hanya bisa diakses oleh owner
    Route::group(['middleware' => ['role:Owner']], function () {

        Route::get('/users/roles/{id}', 'UserController@roles')->name('users.roles');
        Route::put('/users/roles/{id}', 'UserController@setRole')->name('users.set_role');
        Route::post('/users/permission', 'UserController@addPermission')->name('users.add_permission');
        Route::get('/users/roles-permission', 'UserController@rolePermission')->name('users.roles_permission');
        Route::put('/users/permission/{role}', 'UserController@setRolePermission')->name('users.setRolePermission');

    });

    /* Modul Kategori 
    Route::resource('/kategori', 'CategoryController')->except([
        'create', 'show'
    ]); */
    Route::get('/kategori', 'CategoryController@index')->name('kategori.index');
    Route::group(['middleware' => ['permission:Create Category']], function(){
        Route::post('/kategori', 'CategoryController@store')->name('kategori.store');
    });
    Route::group(['middleware' => ['permission:Edit Category']], function(){
        Route::get('/kategori/{kategori}/edit', 'CategoryController@edit')->name('kategori.edit');
        Route::put('/kategori/{kategori}', 'CategoryController@update')->name('kategori.update');
    });
    Route::group(['middleware' => ['permission:Delete Category']], function(){
        Route::delete('/kategori/{kategori}', 'CategoryController@destroy')->name('kategori.destroy');
    });
    
    /* Modul Produk */
    Route::get('/produk', 'ProductController@index')->name('produk.index');
    // Route::get('/produk', 'ProductController@show')->name('produk.show');
    Route::group(['middleware' => ['permission:Create Product|Edit Product|Delete Product']], function(){
        // Route::resource('/produk', 'ProductController');
        Route::get('/produk/create', 'ProductController@create')->name('produk.create');
        Route::post('/produk', 'ProductController@store')->name('produk.store');
    });
    Route::group(['middleware' => ['permission:Edit Product']], function(){
        Route::get('/produk/{produk}/edit', 'ProductController@edit')->name('produk.edit');
        Route::put('/produk/{produk}', 'ProductController@update')->name('produk.update');
    });
    Route::group(['middleware' => ['permission:Delete Product']], function(){
        Route::delete('/produk/{produk}', 'ProductController@destroy')->name('produk.destroy');
    });

    /* Modul Customer */
    Route::get('/customer', 'CustomerController@index')->name('customer.index');
    Route::post('/customer', 'CustomerController@store')->name('customer.store');
    // Route::resource('/customer', 'CustomerController');
    Route::group(['middleware' => ['permission:Edit Customer']], function(){
        Route::get('/customer/{customer}/edit', 'CustomerController@edit')->name('customer.edit');
        Route::put('/customer/{customer}', 'CustomerController@update')->name('customer.update');
    });
    Route::group(['middleware' => ['permission:Delete Customer']], function(){
        Route::delete('/customer/{customer}', 'CustomerController@destroy')->name('customer.destroy');
    });

    /* Modul Cek Ongkir */
    Route::group(['middleware' => ['permission:Check Ongkir']], function(){
        Route::get('/ongkir', 'CheckOngkirController@index')->name('ongkir');
        Route::post('/ongkir', 'CheckOngkirController@check_ongkir');
        Route::get('/cities/{province_id}', 'CheckOngkirController@getCities');
    });

    /* Modul Order */
    Route::resource('/order', 'OrderController');
    Route::get('/tambah', 'OrderController@addOrder')->name('order.tambah');
    Route::get('/checkout', 'OrderController@checkout')->name('order.checkout');
    Route::post('/checkout', 'OrderController@storeOrder')->name('order.storeOrder');
    Route::get('/order/pdf/{invoice}', 'OrderController@invoicePdf')->name('order.pdf');
    
    // Route update profile
    Route::get('setting', 'SettingController@profileSetting')->name('setting.profile');
    Route::post('setting', 'SettingController@updateProfile')->name('setting.updateProfile');
    
    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::resource('/role', 'RoleController')->except([
        'create', 'show', 'edit', 'update'
    ]);

    Route::resource('/users', 'UserController')->except([
        'show'
    ]);

    Route::get('/status/update', 'UserController@updateStatus')->name('user.updateStatus');

    // Route::resource('/type', 'TypeController')->except([
    //     'create', 'show', 'edit', 'update'
    // ]);

    
     

    
});