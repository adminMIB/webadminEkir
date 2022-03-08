<?php

use Illuminate\Support\Facades\Route;

Route::group( ['middleware' => ['custom_auth']], function() {
	//Platform Groups
	Route::get('/group', 'Platform\GroupsController@index')->name('group');
	Route::get('/group/create', 'Platform\GroupsController@create')->name('create_group');
	Route::post('/group/save', 'Platform\GroupsController@store')->name('store_group');	
	Route::get('/group/{id}/edit', 'Platform\GroupsController@edit')->name('edit_group');
	Route::post('/group/{id}/update', 'Platform\GroupsController@update')->name('update_group');
	Route::get('/group/{id}/delete', 'Platform\GroupsController@destroy')->name('delete_group');
	
	//Platform Applications
	Route::get('/application', 'Platform\AppController@index')->name('application');
	Route::get('/application/create', 'Platform\AppController@create')->name('create_application');
	Route::post('/application/save', 'Platform\AppController@store')->name('store_application');	
	Route::get('/application/{id}', 'Platform\AppController@read')->name('read_application');
	Route::get('/application/{id}/edit', 'Platform\AppController@edit')->name('edit_application');
	Route::post('/application/{id}/update', 'Platform\AppController@update')->name('update_application');	
	Route::get('/application/{id}/delete', 'Platform\AppController@destroy')->name('delete_application');

	//Roles
	Route::get('/role', 'Platform\RolesController@index')->name('role');
	Route::get('/role/create', 'Platform\RolesController@create')->name('create_role');
	Route::post('/role/save', 'Platform\RolesController@store')->name('store_role');	
	Route::get('/role/{id}/edit', 'Platform\RolesController@edit')->name('edit_role');
	Route::post('/role/{id}/update', 'Platform\RolesController@update')->name('update_role');
	Route::get('/role/{id}/delete', 'Platform\RolesController@destroy')->name('delete_role');

	//Role To Applications
	Route::get('/role-to-application', 'Platform\RolesController@role2AppList')->name('role_to_application');
	Route::post('/role-to-application/{id}/update', 'Platform\RolesController@updateRole2App')->name('update_role_to_application');
	//Role To Permissions
	Route::get('/role-to-permission', 'Platform\RolesController@role2PermissionList')->name('role_to_permission');
	Route::post('/role-to-permission/{id}/update', 'Platform\RolesController@updateRole2Permission')->name('update_role_to_permission');

});