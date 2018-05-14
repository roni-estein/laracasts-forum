<?php

Route::get('/', function () {
//    return view('welcome');
    echo phpinfo();
});


Route::resource('threads','ThreadsController',['except' =>['destroy','show','update','edit']]);

Route::get('/threads/{channel}/{thread}/replies','RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies','RepliesController@store');
Route::get('/threads/{channel}','ThreadsController@index')->name('threads.show');
Route::get('/threads/{channel}/{id}','ThreadsController@show')->name('threads.show');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->name('threads.subscribe');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->name('threads.unsubscribe');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');
Route::post('replies/{reply}/favorites', 'FavoritesController@store');


Route::get('profiles/{user}','ProfilesController@show')->name('profile');

Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');

