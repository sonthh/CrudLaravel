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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->group(function () {
    Route::prefix('article')->group(function () {
        Route::get('index', 'AdminArticleController@index');

        Route::get('add', 'AdminArticleController@add');
        Route::post('add', 'AdminArticleController@addPost');

        Route::get('edit/{articleId}', 'AdminArticleController@edit');
        Route::post('edit/{articleId}', 'AdminArticleController@editPost');

        Route::get('delete/{articleId}', 'AdminArticleController@delete');
        Route::post('toggleStatus', 'AdminArticleController@toggleStatus');


    });
});
Route::get('update', 'AdminArticleController@update');
