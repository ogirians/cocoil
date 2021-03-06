<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', 'Auth\LoginController@login');

//actions
Route::post('/action-coil/store','API\ActionController@store')->name('action-store');
Route::get('/action-coil-get/{id?}','API\ActionController@getAction')->name('action-get');
Route::get('/action-coil-detail/{id?}','API\ActionController@getActionDetail')->name('action-get-detail');


//get coil data
Route::resource('/coils', 'API\CoilController')->except(['show']);
Route::get('/coils/noplace', 'API\CoilController@coilnoplace')->name('coil.noplace');
Route::get('/coils/detail/{id}', 'API\CoilController@detail')->name('coil.detail');


Route::group(['middleware' => 'auth:api'], function() {
   
    



    //gudangs
    Route::resource('/gudangs', 'API\GudangController')->except(['show']);
    Route::get('/gudangs/getData', 'API\GudangController@getData')->name('getData');


    //blok
    Route::resource('/bloks', 'API\BlokController')->except(['show']);

    //get coil location
    Route::resource('/locations', 'API\locationController')->except(['create', 'show', 'update']);
    Route::post('/locations/{id}', 'API\locationController@update')->name('locations.update');
    Route::post('/locations/setCoil/{id}', 'API\locationController@SetCoil')->name('locations.setCoil');

    //roles
    Route::get('roles', 'API\RolePermissionController@getAllRole')->name('roles');
    
    //permisions
    Route::get('permissions', 'API\RolePermissionController@getAllPermission')->name('permission');
    Route::post('role-permission', 'API\RolePermissionController@getRolePermission')->name('role_permission');
    Route::post('set-role-permission', 'API\RolePermissionController@setRolePermission')->name('set_role_permission');
    Route::post('set-role-user', 'API\RolePermissionController@setRoleUser')->name('user.set_role');

    //user
    Route::get('user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
    Route::get('user-lists', 'API\UserController@userLists')->name('user.index');

    //get employee
    Route::resource('/Employees', 'API\UserController')->except(['create', 'show', 'update']);
    Route::post('/Employees/{id}', 'API\UserController@update')->name('Employees.update');


    //import
    Route::post('/import','ImportController@import')->name('import');

    //notifikasi
    Route::resource('notification-gudang', 'API\NotificationGudangController')->except(['create', 'destroy']);
    Route::resource('notification-action', 'API\NotificationActionController')->except(['create', 'destroy']);


    //action
    Route::post('/action-coil/terima/','API\ActionController@confirm')->name('action-confirm');
    Route::post('/action-coil/tolak/','API\ActionController@tolak')->name('action-tolak');  
    Route::post('/action-coil/baca/','API\ActionController@tolak')->name('action-tolak');  
   
   
});
