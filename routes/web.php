<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/forbidden', function () {
    return view('forbidden');
});

Auth::routes();

Route::group( ['middleware' => ['custom_auth']], function() {
	Route::get('/', 'DashboardController@index')->name('dashboard');
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	
	Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
	
	//Management User
	Route::get('/my', 'User\MyController@profile')->name('profile');


	//Management Users
	Route::get('/user', 'User\UserController@index')->name('akun_user');
	Route::get('/user/create', 'User\UserController@create')->name('create_akun_user');
	Route::post('/user/save', 'User\UserController@store')->name('store_akun_user');
	Route::get('/user/{id}/edit', 'User\UserController@edit')->name('edit_akun_user');
	Route::post('/user/{id}/update', 'User\UserController@update')->name('update_akun_user');	
	Route::get('/user/{id}/delete', 'User\UserController@destroy')->name('delete_akun_user');


});

/*
* Master Data
* Provinsi Change
*/
Route::post('/master/kota-kabupaten', 'Master\MasterDataController@getKotaKabupaten')->name('kota_kabupaten');
Route::post('/master/kecamatan', 'Master\MasterDataController@getKecamatan')->name('kecamatan');
Route::post('/master/keluarahan-desa', 'Master\MasterDataController@getKelurahanDesa')->name('kelurahan_desa');
