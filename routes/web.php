<?php


use App\Invite;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require 'api-front.php';

Route::get("invite/{invite}/join", "InviteController@create");

Route::post("invite/{invite}/join", "InviteController@store");

Route::get("test", function(){
   $project = \App\Project::with("tasks")->first();

   return $project;
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
