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

// Gente disponible en el chat
Route::get('/people', 'PeopleController@index');

// Contactos disponibles
Route::get('/contacts', 'ContactController@index');

// Ruta para actualizar el perfil
Route::put('/profile/{profile_id}', 'ProfileController@update');

// Datos de usuario y su perfil
Route::get('/profile', 'ProfileController@index');

// Ruta para postear nuevos mensajes
Route::post('/conversation/{conversation_id}', 'ConversationController@storeMessage');

// Conversaciones de usuario
Route::get('/conversations', 'ConversationController@index');

// Ruta para recuperar los nuevos mensajes recibidos.
Route::get('/conversations/update', 'ConversationController@fetchLastMessages');