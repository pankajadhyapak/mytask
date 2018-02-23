<?php


use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require 'api-front.php';


Route::get("test", function(){
   return response()->json([
       "success" => true,
       "data" => [
           "name" => "pankaj"
       ]
   ]);
});
Route::view('/', 'welcome')->middleware('guest');

Route::resource('team', 'TeamController');
Route::resource('project', 'ProjectController');
Route::resource('module', 'ModuleController');
Route::resource('task', 'TaskController');
Route::resource('comment', 'CommentController');
Route::resource('worklog', 'WorkLogController');

Auth::routes();

Route::get('/dashboard/{vue_capture?}', 'HomeController@dashboard')
    ->name('dashboard')
    ->where('vue_capture', '[\/\w\.-]*');
Route::get('/home', 'HomeController@index')->name('home');
