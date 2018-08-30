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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/link', 'ContentController@processLink');

Route::resource('/pages', 'PageController');

Route::post('/pages/{page}/ads', 'PageController@addAd');

Route::resource('/ads', 'AdController');

Route::post('/autocomplete', 'AdController@autocomplete');

Route::get('/f/{page}', 'ContentController@framePage');

Route::get('/{page}', 'ContentController@getPage');
