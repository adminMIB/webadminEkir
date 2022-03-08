<?php

use Illuminate\Support\Facades\Route;

Route::group( ['middleware' => ['custom_auth']], function() {
	//Persyaratan
	Route::get('/persyaratan', 'Sambat\PersyaratanController@index')->name('persyaratan');
	Route::get('/persyaratan/create', 'Sambat\PersyaratanController@create')->name('create_persyaratan');
	Route::post('/persyaratan/save', 'Sambat\PersyaratanController@store')->name('store_persyaratan');	
	Route::get('/persyaratan/{id}', 'Sambat\PersyaratanController@detail')->name('read_persyaratan');
	Route::get('/persyaratan/{id}/edit', 'Sambat\PersyaratanController@edit')->name('edit_persyaratan');
	Route::post('/persyaratan/{id}/update', 'Sambat\PersyaratanController@update')->name('update_persyaratan');
	Route::get('/persyaratan/{id}/delete', 'Sambat\PersyaratanController@destroy')->name('delete_persyaratan');
	

	//Panduan
	Route::get('/panduan', 'Sambat\PanduanController@index')->name('panduan');
	Route::get('/panduan/create', 'Sambat\PanduanController@create')->name('create_panduan');
	Route::post('/panduan/save', 'Sambat\PanduanController@store')->name('store_panduan');	
	Route::get('/panduan/{id}/edit', 'Sambat\PanduanController@edit')->name('edit_panduan');
	Route::post('/panduan/{id}/update', 'Sambat\PanduanController@update')->name('update_panduan');
	Route::get('/panduan/{id}/delete', 'Sambat\PanduanController@destroy')->name('delete_panduan');


	//Mekanisme
	Route::get('/mekanisme', 'Sambat\MekanismeController@index')->name('mekanisme');
	Route::get('/mekanisme/{id}/edit', 'Sambat\MekanismeController@edit')->name('edit_mekanisme');
	Route::post('/mekanisme/{id}/update', 'Sambat\MekanismeController@update')->name('update_mekanisme');

	//Video
	Route::get('/video', 'Sambat\VideoController@index')->name('video');
	Route::get('/video/create', 'Sambat\VideoController@create')->name('create_video');
	Route::post('/video/save', 'Sambat\VideoController@store')->name('store_video');
	Route::get('/video/{id}/edit', 'Sambat\VideoController@edit')->name('edit_video');
	Route::post('/video/{id}/update', 'Sambat\VideoController@update')->name('update_video');	
	Route::get('/video/{id}/delete', 'Sambat\VideoController@destroy')->name('delete_video');

	//Pengumuman
	Route::get('/pengumuman', 'Sambat\PengumumanController@index')->name('pengumuman');
	Route::get('/pengumuman/create', 'Sambat\PengumumanController@create')->name('create_pengumuman');
	Route::post('/pengumuman/save', 'Sambat\PengumumanController@store')->name('store_pengumuman');	
	Route::get('/pengumuman/{id}', 'Sambat\PengumumanController@detail')->name('read_pengumuman');
	Route::get('/pengumuman/{id}/edit', 'Sambat\PengumumanController@edit')->name('edit_pengumuman');
	Route::post('/pengumuman/{id}/update', 'Sambat\PengumumanController@update')->name('update_pengumuman');
	Route::get('/pengumuman/{id}/delete', 'Sambat\PengumumanController@destroy')->name('delete_pengumuman');

	//Laporan Kode Bayar Pendaftaran
	Route::get('laporan/laporan-pendaftaran', 'Sambat\LapPendaftaranController@index')->name('laporan_pendaftaran');
	//Laporan Pengaduan
	Route::get('laporan/saran-pengaduan', 'Sambat\SaranPengaduanController@index')->name('saran_pengaduan');

});