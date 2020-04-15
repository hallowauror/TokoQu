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

    // Route yang bisa diakses oleh semua user
    Route::resource('/kategori', 'CategoryController')->except([
        'create', 'show'
    ]);

    Route::resource('/produk', 'ProductController');

    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::resource('/role', 'RoleController')->except([
        'create', 'show', 'edit', 'update'
    ]);

    Route::resource('/users', 'UserController')->except([
        'show'
    ]);

    Route::resource('/customer', 'CustomerController');

    Route::resource('/type', 'TypeController')->except([
        'create', 'show', 'edit', 'update'
    ]);

    Route::resource('/order', 'OrderController');
    
    //Route cek ongkir
    Route::get('/ongkir', 'CheckOngkirController@index')->name('ongkir');
    Route::post('/ongkir', 'CheckOngkirController@check_ongkir');
    Route::get('/cities/{province_id}', 'CheckOngkirController@getCities'); 
    
    Route::get('setting', 'SettingController@profileSetting')->name('setting.profile');
    Route::post('setting', 'SettingController@updateProfile')->name('setting.updateProfile');
});


