<?php

use App\Module;
use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/api/team", function(Request $request){

    $team = Team::create([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'created_by' => auth()->id()
    ]);
    $members = collect($request->get('members'))->pluck("id");
    $members[] = auth()->id();
    $team->members()->attach(array_unique($members->toArray()));

    return $team;
});
Route::get("/api/users", function(){
    return User::all();
});

Route::get("api/user/search", function(){
   $query = request('q');
   if($query){
       return \App\User::where("name", "LIKE", "%$query%")->orWhere("email", "like", "%$query%")->get();
   }
});
Route::get("/api/dashboard/init", function(){
    return auth()->user()->load("teams.projects","teams.members")['teams'];
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

Route::post("/api/task/{task}/comment", function(Task $task, Request $request){
    $allowedType = ["Task"];
    $type = $request->get('type');
    if (in_array($type, $allowedType)) {
        $type = '\App\\' . $type;
        $model = $type::find($request->get('type_id'));
        $newComment = $model->comments()->create([
            "body" => $request->get('body'),
            "user_id" => auth()->id()
        ]);

        return $newComment->load("owner");
    }

    throw new \Exception("{$type} Type is not commentable");
});