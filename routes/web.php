<?php


Route::get('/', function () {
    return view('welcome');
});

Route::resource('threads','ThreadsController');
Route::resource('threads.replies','RepliesController');

Auth::routes();

Route::get('/home', 'HomeController@index');
