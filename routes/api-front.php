<?php

use App\Module;
use App\Project;
use App\Task;
use App\Team;
use Illuminate\Support\Facades\Route;

Route::get("/api/dashboard/init", function(){

    return [
        "teams" => auth()->user()->teams,
        "projects" => auth()->user()->projects()
    ];
});

Route::get("/api/team/{team}", function(Team $team){
    return $team->load('members', 'projects', 'owner');
});

Route::get("/api/project/{project}", function(Project $project){
    return $project->load('modules.tasks', 'team.members');
});

Route::get("/api/module/{module}/tasks", function (Module $module){
    return $module->load('tasks');
});

Route::get("/api/task/{task}", function (Task $task){
    return $task->load('worklogs', 'comments');
});