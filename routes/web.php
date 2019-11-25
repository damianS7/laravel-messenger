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

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');

Auth::routes();
// Contactos (GET/ADD/DELETE)
Route::get('/contacts', 'ContactController@index');
Route::get('/profile', 'ProfileController@index');
Route::put('/profile/{profile_id}', 'ProfileController@update');
Route::get('/conversation/{contact_id}', 'ConversationController@index');
// Route::post('/contacts/{user_id}', 'ContactController@store');
// Route::get('/contacts/{contact_id}', 'ContactController@destroy');

// Messages (GET/ADD)
// Route::get('/messages/', 'MessengerController@index');
// Route::post('/messages/', 'MessengerController@store');