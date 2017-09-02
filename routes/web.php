<?php


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::resource('threads','ThreadsController');
Route::resource('threads.replies','RepliesController');


Route::get('/home', 'HomeController@index');
