<?php

Route::get('/', function () {
//    return view('welcome');
    echo phpinfo();
});


Route::resource('threads','ThreadsController',['except' =>['destroy','show','update','edit']]);

Route::get('/threads/{channel}/{thread}/replies','RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies','RepliesController@store')->name('replies.store');
Route::get('/threads/{channel}','ThreadsController@index')->name('threads.show');
Route::get('/threads/{channel}/{id}','ThreadsController@show')->name('threads.show');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->name('threads.subscribe');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->name('threads.unsubscribe');


Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');

Route::get('/profiles/{user}/notifications/', 'UserNotificationController@index')->name('notification.unread.list');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy')->name('notification.unread.read');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');
Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::post('replies/{reply}/best', 'BestRepliesController@store')->name('replies.best.store');


Route::get('profiles/{user}','ProfilesController@show')->name('profile');

Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::get('/api/users','Api\UsersController@index')->name('user.search');
Route::post('/api/users/{user}/avatar','Api\UserAvatarController@store')->name('avatar');
Route::get('/register/confirm','Auth\RegisterConfirmationController@index')->name('register.confirm');
