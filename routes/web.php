<?php


Route::get('/', function () {
    return view('welcome');
});

Route::resource('threads','ThreadController');

Auth::routes();

Route::get('/home', 'HomeController@index');
