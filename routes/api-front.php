<?php

use App\Module;
use App\Project;
use App\Status;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/api/team/{team}/project", function(Team $team, Request $request){

    $project = Project::create([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'team_id' => $team->id,
        'created_by' => auth()->id()
    ]);

    $project->modules()->create([
        'project_id' => $project->id,
        'created_by' => auth()->id()
    ]);

    foreach (Status::$defaultStatus as $status) {
        $project->statuses()->create($status);
    }

    return $project;
});

Route::patch("/api/task/{task}/complete", function (Task $task){
    return $task->markAsComplete(request('project_id'));
});

Route::post("/api/task/{task}/work-log", function(Task $task, Request $request){

    $log =  $task->worklogs()->create([
        'user_id' => auth()->id(),
        'comment' => $request->get('comment'),
        'hours' => $request->get('hours'),
        'date' => $request->get('date')
    ]);
    return $log->load("owner");

});
Route::post("/api/team", function(Request $request){

    $team = Team::create([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'created_by' => auth()->id()
    ]);
    $members[] = auth()->id();
    $members = array_merge($members, collect($request->get('members'))->pluck("id")->toArray());
    $team->members()->attach(array_unique($members));
    return $team->load("projects","members");
});
Route::get("/api/users", function(){
    if(request()->has("me")){
        return User::all();
    }
    return User::where("id","!=",auth()->id())->get();
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

Route::post("/api/task", function(Request $request){

    validator($request->toArray(), [
        'name' => 'required',
        'module_id' => 'required',
        'project_id' => 'required'
    ]);

    $status = Status::where("statusable_type", "App\Project")
                ->where("statusable_id", $request->get('project_id'))
                ->where("default", 1)->firstOrFail();

    $task = Task::create([
        'name' => $request->get('name'),
        'module_id' => $request->get('module_id'),
        'status_id' => $status->id,
        'created_by' => auth()->id()
    ]);
    return $task->load('owner');
});

Route::patch("/api/task/{task}", function(Task $task){
    $task->fill(request()->all());
    $task->save();
    return $task;
});

Route::delete("/api/task/{task}", function(Task $task){
    $task->delete();
    return ["success" => true];
});
Route::get("/api/project/{project}", function(Project $project){
    return $project->load('modules.tasks', 'team.members', 'statuses');
});

Route::get("/api/module/{module}/tasks", function (Module $module){
    return $module->load('tasks');
});

Route::put("/api/task/{task}", function (Task $task){

    $task->fill(request()->except(['statues','is_completed', 'assigned', 'owner','status', 'worklogs','comments', 'module']));

    if(\request()->has("assigned")){
        $task->assigned_to = \request()->get('assigned')['id'];
    }

    if(\request()->has("status")){
        $t = collect(\request()->get("statues"))->where("id",\request()->get('status_id'))->first();

        if($t && $t['defines_complete']){
            $task->is_completed = true;
        }else{
            $task->is_completed = false;
        }

        $task->status_id = \request()->get('status_id');
    }

    $task->update();
    $data['task'] = $task->fresh()->load('worklogs', 'comments');
    $data['status'] = \request()->get("status");
    return $data;
});

Route::get("/api/task/{task}", function (Task $task){
    $data['task'] = $task->load('worklogs', 'comments');
    $projectId = $task->module->project_id;
    $data['status'] = Status::where(["statusable_type" => "App\Project", "statusable_id" => $projectId])->get();
    return $data;
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
