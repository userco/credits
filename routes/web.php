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
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
	Route::get('/credits_list', [
		'as'        => 'credits_list',
		'middleware' => ['web'],
		'uses'      => 'CreditController@getList',
	 ]);
	Route::post('/credits_list', [
		'as'        => 'credits_list',
		'middleware' => ['web'],
		'uses'      => 'CreditController@postList',
	 ]);
	Route::get('/invest/{creditId}', [
		'as'        => 'invest',
		'middleware' => ['web'],
		'uses'      => 'CreditController@getInvest',
	 ]);
	Route::post('/invest/{creditId}', [
		'as'        => 'invest',
		'middleware' => ['web'],
		'uses'      => 'CreditController@postInvest',
	 ]);
	Route::get('/home', 'HomeController@index')->name('home');
});