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
/* 严格按照 RESTful 架构对路由进行设计 */
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('/signup', 'UsersController@create')->name('signup');
Route::resource('users','UsersController');
/* resource 资源路由 等价于下面 7 个路由 */
// Route::get('/users', 'UsersController@index')->name('users.index');
// Route::get('/users/{user}', 'UsersController@show')->name('users.show'); // 显示用户个人信息
// Route::get('/users/create', 'UsersController@create')->name('users.create'); // 创建用户
// Route::post('/users', 'UsersController@store')->name('users.store'); // 处理用户创建的相关逻辑
// Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
// Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
// Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');

// 会话
Route::get('login', 'SessionsController@create')->name('login'); // 登录页
Route::post('login', 'SessionsController@store')->name('login'); // 登录(创建新会话)
Route::delete('logout', 'SessionsController@destroy')->name('logout'); //登出(退出登录)