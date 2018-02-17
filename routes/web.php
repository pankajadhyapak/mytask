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

use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get("test", function(){
   dd(Status::$defaultStatus);
});
Route::view('/', 'welcome')->middleware('guest');

Route::resource('team', 'TeamController');
Route::resource('project', 'ProjectController');
Route::resource('module', 'ModuleController');
Route::resource('task', 'TaskController');
Route::resource('comment', 'CommentController');
Route::resource('worklog', 'WorkLogController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
