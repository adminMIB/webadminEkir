<?php

use Illuminate\Support\Facades\Route;

Route::group( ['middleware' => ['custom_auth']], function() {
    //Pelayanan
    Route::get('/pelayanan', 'Epkb\PelayananController@index')->name('pelayanan');
    Route::get('/pelayanan/create', 'Epkb\PelayananController@create')->name('create_pelayanan');
    Route::post('/pelayanan/save', 'Epkb\PelayananController@store')->name('store_pelayanan');	
	Route::get('/pelayanan/{id}', 'Epkb\PelayananController@detail')->name('read_pelayanan');
	Route::get('/pelayanan/{id}/edit', 'Epkb\PelayananController@edit')->name('edit_pelayanan');
	Route::post('/pelayanan/{id}/update', 'Epkb\PelayananController@update')->name('update_pelayanan');
	Route::get('/pelayanan/{id}/delete', 'Epkb\PelayananController@destroy')->name('delete_pelayanan');

    //Lokasi Pengujian
    Route::get('/lokasi', 'Epkb\LokasiController@index')->name('lokasi');
	Route::get('/lokasi/create', 'Epkb\LokasiController@create')->name('create_lokasi');
	Route::post('/lokasi/save', 'Epkb\LokasiController@store')->name('store_lokasi');	
	Route::get('/lokasi/{id}/edit', 'Epkb\LokasiController@edit')->name('edit_lokasi');
	Route::post('/lokasi/{id}/update', 'Epkb\LokasiController@update')->name('update_lokasi');
	Route::get('/lokasi/{id}/delete', 'Epkb\LokasiController@destroy')->name('delete_lokasi');

    //Informasi dan Berita
	Route::get('/informasi-dan-berita', 'Epkb\InformasiController@index')->name('informasi');
	Route::get('/informasi-dan-berita/create', 'Epkb\InformasiController@create')->name('create_informasi');
	Route::post('/informasi-dan-berita/save', 'Epkb\InformasiController@store')->name('store_informasi');	
	Route::get('/informasi-dan-berita/{id}', 'Epkb\InformasiController@detail')->name('read_informasi');
	Route::get('/informasi-dan-berita/{id}/edit', 'Epkb\InformasiController@edit')->name('edit_informasi');
	Route::post('/informasi-dan-berita/{id}/update', 'Epkb\InformasiController@update')->name('update_informasi');
	Route::get('/informasi-dan-berita/{id}/delete', 'Epkb\InformasiController@destroy')->name('delete_informasi');

    //Video
    Route::get('/video', 'Epkb\VideoController@index')->name('video');
	Route::get('/video/create', 'Epkb\VideoController@create')->name('create_video');
	Route::post('/video/save', 'Epkb\VideoController@store')->name('store_video');
	Route::get('/video/{id}/edit', 'Epkb\VideoController@edit')->name('edit_video');
	Route::post('/video/{id}/update', 'Epkb\VideoController@update')->name('update_video');	
	Route::get('/video/{id}/delete', 'Epkb\VideoController@destroy')->name('delete_video');

    //Member
    Route::get('/member', 'Epkb\MemberController@index')->name('member');
	Route::get('/member/create', 'Epkb\MemberController@create')->name('create_member');
	Route::post('/member/save', 'Epkb\MemberController@store')->name('store_member');
    Route::get('/member/{id}', 'Epkb\MemberController@detail')->name('read_member');
	Route::get('/member/{id}/edit', 'Epkb\MemberController@edit')->name('edit_member');
	Route::post('/member/{id}/update', 'Epkb\MemberController@update')->name('update_member');	
	Route::get('/member/{id}/delete', 'Epkb\MemberController@destroy')->name('delete_member');

	//Kendaraan Pelanggan
	Route::get('kendaraan-pelanggan', 'Epkb\CustKendaraanController@index')->name('kendaraan_pelanggan');
	Route::get('kendaraan-pelanggan/{id}/ubah-data', 'Epkb\CustKendaraanController@edit')->name('edit_kendaraan_pelanggan');
	Route::post('kendaraan-pelanggan/{id}/update', 'Epkb\CustKendaraanController@update')->name('update_kendaraan_pelanggan');	
	Route::get('kendaraan-pelanggan/{id}/delete', 'Epkb\CustKendaraanController@destroy')->name('delete_kendaraan_pelanggan');

	//Booking
	Route::get('booking-pengujian', 'Epkb\BookingController@index')->name('booking_pengujian');
	Route::get('booking-pengujian/{id}', 'Epkb\BookingController@detail')->name('read_booking_pengujian');
	//Terima Pengajuan
	Route::post('booking-submit', 'Epkb\BookingController@submit')->name('store_booking_pengujian');
	//Tolak Pengajuan
	Route::post('tolak-pengajuan', 'Epkb\BookingController@tolak')->name('delete_booking_pengujian');
	//Kirim Pesan Notifikasi
	Route::post('kirim-pesan-booking', 'Epkb\BookingController@kirimPesan')->name('update_booking_pengujian');

});