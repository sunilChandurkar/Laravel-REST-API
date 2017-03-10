<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

//get a list of interview questions
Route::get('/questions', 'ApiController@index');

//get a specific question
Route::get('/question/{id}', 'ApiController@show')->where('id', '[0-9]+');

//delete a question
Route::delete('/question/{id}', 'ApiController@destroy')->where('id', '[0-9]+');

//update existing question
Route::put('/question/{id}', 'ApiController@update')->where('id', '[0-9]+');

//create a question and answer
Route::post('/question', 'ApiController@store')->middleware('auth.basic');
