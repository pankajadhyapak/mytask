<?php

use App\Invite;
use App\Module;
use App\Project;
use App\Status;
use App\Tag;
use App\Task;
use App\Team;
use App\User;
use App\WorkLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

function rand_color()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

Route::get("/api/all-tags", function (){
   return Tag::all();
});
Route::any("/api/module/{module}", function (Module $module){
    $defm = Module::where("project_id", $module->project_id)->first();
    $tasks = $module->tasks()->update(["module_id" => $defm->id]);
    $module->delete();
    return ["success" => true];
});
Route::post("/api/{pid}/module", function ($pid){
    $module = Module::create([
        "name" => request("name"),
        "project_id" => $pid,
        "created_by" => auth()->id()
    ]);
    return $module->load("tasks");
});

Route::any("/api/{team}/remove-from-team", function (Team $team){
    try{
        $userId = request('user_id');
        if(!is_array($userId)){
            Task::where("assigned_to", $userId)->update(['assigned_to' => null]);
            $team->members()->detach(\request('user_id'));
            return response(["success" => true]);
        }
        return response(["success" => false], 422);
    }catch (Exception $e){
        return response(["success" => false], 422);
    }
});

Route::post("/api/{team}/add-to-team", function (Team $team){
    try{
        //TODO check attached array count
        $team->members()->syncWithoutDetaching(\request('user_id'));
        return [
            "team_id" => $team->id,
            "user_id" =>\request('user_id'),
            "created_at"=> (string)now(),
            "updated_at"=> (string)now()
        ];
    }catch (Exception $e){
        return response(["success" => false], 422);
    }
});
Route::get("api/test", function(){
    return Invite::with("by")->first();
});

Route::post("/api/invite", function (){
    $record = User::where("email", request('email'))->exists();
    if(!$record){
        $in = Invite::where([
            "team_id" => request("team_id"),
            "email" => request("email")
        ])->exists();
        if($in){
            return response([
                "success" => false,
                "message" => "Invite Already Sent to ".request("name")
            ], 422);
        }
        $invite = new Invite;
        $invite->invited_by = auth()->id();
        $invite->team_id = request("team_id");
        $invite->name = request("name");
        $invite->email = request("email");
        $invite->save();
        return $invite;
    }
    return response([
        "success" => false,
        "message" => "User ".request("email")." Already Exists"
    ], 422);
});

Route::get("api/search/addmember/{team}", function (Team $team) {
    $existingUserIds = $team->members()->pluck("user_id");
    $searchKey = request('search');
    $users = User::Where(function ($query) use ($searchKey) {
        $query->where("name", "LIKE", "%$searchKey%")
            ->orWhere("email", "LIKE", "%$searchKey%");
    });
    if (count($existingUserIds)) {
        $users = $users->whereNotIn("id", $existingUserIds);
    }
    $users = $users->get();
    return $users;
});
Route::get("/api/time-sheet", function (Request $request) {
    $data = [];

    $loggedTasks = WorkLog::with("loggable.module.project")
        ->whereBetween('date', [$request->get("from_date"), $request->get("to_date")]);
    if ($request->has("currentUsers")) {
        $users = [];
        foreach ($request->get('currentUsers') as $userId) {
            $users[] = (int)$userId;
        }
        $loggedTasks->whereIn("user_id", $users);
    }
    $loggedTasks = $loggedTasks->get();
    //return $loggedTasks;

    foreach ($loggedTasks as $task) {
        $temp = [];
        $temp['hours'] = $task->hours;
        $temp['email'] = $task->owner->email;
        $temp['date'] = Carbon::parse($task->date)->format("d-m-Y");
        $taskt = [];
        $taskt['name'] = $task['loggable']['name'];
        $taskt['id'] = $task->loggable->id;
        $temp['task'] = $taskt;

        $modulet = [];
        $modulet['name'] = $task['loggable']['module']['name'];
        $modulet['id'] = $task['loggable']['module']['id'];
        $temp['module'] = $modulet;

        $projectt = [];
        $projectt['name'] = $task['loggable']['module']['project']['name'];
        $projectt['id'] = $task['loggable']['module']['project']['id'];
        $temp['project'] = $projectt;


        $statust = [];
        $statust['name'] = $task['loggable']['status']['name'];
        $statust['id'] = $task['loggable']['status']['id'];
        $temp['status'] = $statust;

        $data[] = $temp;
    }
    $data = collect($data);
    $final = [];
    foreach ($data->groupBy("email") as $email => $datat) {
        $t = [];
        $t['email'] = $email;
        $t['data'] = $datat;
        $final[] = $t;
    }
    return $final;
});
Route::get("/api/project/{project}/report", function (Project $project) {
    $all = $project->load('tasks');
    $project = $project->tasks;
    $report = [];
    $report['total'] = count($project);
    $status = [];
    foreach ($project->groupBy("status.name") as $s => $t) {
        $temp = [];
        $temp['name'] = $s;
        $temp['count'] = $t->count();

        $temp['color'] = "#36a2eb";
        $temp['order'] = rand(2, 999);

        if ($t->first()->status['defines_complete']) {
            $temp['color'] = "#00bf9c";
            $temp['order'] = 99999;
        }
        if ($t->first()->status['default']) {
            $temp['color'] = "#ffcd56";
            $temp['order'] = 1;
        }

        $status[] = $temp;
    }
    $report['status'] = collect($status)->sortBy("order")->values()->all();
    $users = [];
    foreach ($project->groupBy("assigned.email") as $s => $t) {
        $temp = [];
        $temp['email'] = $s == "" ? "Un Assigned" : $s;
        $temp['color'] = rand_color();
        $temp['count'] = $t->count();
        $users[] = $temp;
    }
    $report['users'] = $users;
    return $report;
});

Route::post("/api/team/{team}/project", function (Team $team, Request $request) {

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

Route::patch("/api/task/{task}/complete", function (Task $task) {
    return $task->markAsComplete(request('project_id'));
});

Route::post("/api/task/{task}/work-log", function (Task $task, Request $request) {

    $log = $task->worklogs()->create([
        'user_id' => auth()->id(),
        'comment' => $request->get('comment'),
        'hours' => $request->get('hours'),
        'date' => $request->get('date')
    ]);
    return $log->load("owner");

});
Route::post("/api/team", function (Request $request) {

    $team = Team::create([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'created_by' => auth()->id()
    ]);
    $members[] = auth()->id();
    $members = array_merge($members, collect($request->get('members'))->pluck("id")->toArray());
    $team->members()->attach(array_unique($members));
    return $team->load("projects", "members");
});
Route::get("/api/users", function () {
    $user = new User;

    if (request()->get("me", false)) {
        $user = $user->all();
    }else{
        $user = $user->where("id", "!=", auth()->id())->get();
    }
    if(\request()->has("team")){
        $userIds = \DB::table("team_members")->where("team_id", request('team'))->pluck("user_id");
        if(count($userIds)){
            $user = User::whereIn("id", $userIds)->get();
        }else{
            $user = [];
        }
    }
    return $user;
});

Route::get("api/user/search", function () {
    $query = request('q');
    if ($query) {
        return \App\User::where("name", "LIKE", "%$query%")->orWhere("email", "like", "%$query%")->get();
    }
});
Route::get("/api/dashboard/init", function () {
    return auth()->user()->load("teams.projects", "teams.members")['teams'];
});

Route::get("/api/team/{team}", function (Team $team) {
    return $team->load('members', 'projects', 'owner');
});

Route::post("/api/task", function (Request $request) {

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
    return $task->load('owner', 'status');
});

Route::patch("/api/task/{task}", function (Task $task) {
    $task->fill(request()->all());
    $task->save();
    return $task;
});

Route::delete("/api/task/{task}", function (Task $task) {
    $task->delete();
    return ["success" => true];
});
Route::get("/api/project/{project}", function (Project $project) {
    return $project->load('modules.tasks', 'team.members', 'statuses');
});

Route::get("/api/module/{module}/tasks", function (Module $module) {
    return $module->load('tasks');
});

Route::put("/api/task/{task}", function (Task $task) {

    $task->fill(request()->except(['statues', 'is_completed', 'assigned', 'owner', 'status', 'worklogs', 'comments', 'module', 'tags']));

    if (\request()->has("assigned")) {
        $task->assigned_to = \request()->get('assigned')['id'];
    }

    if(\request()->has("tags")){
        $newTags = [];
        $allTags = \request()->get("tags");
        foreach ($allTags as $index => $tag) {
            if(!array_key_exists("id", $tag)){
                $newTags[] = $tag;
                unset($allTags[$index]);
            }
        }
        $tagIds = collect($allTags)->pluck("id")->toArray();
        $task->tags()->sync($tagIds);

        foreach ($newTags as $tag){
            $task->tags()->create($tag);
        }
//        dump($allTags);
//        $newTags = [];
//        foreach ($allTags as $index => $tag){
//            if(is_string($tag)){
//                $newTags[] = $tag;
//                unset($allTags[$index]);
//            }
//        }
//        $tagId = collect($allTags)->pluck("id")->toArray();
//        dd($tagId, $newTags);
////        $task->tags()->sync(collect($allTags)->pluck("id")->toArray());
////
////        foreach ($newTags as $tag){
////            $task->tags()->create(["name" => $tag]);
////        }
    }

    if (\request()->has("status")) {
        $t = collect(\request()->get("statues"))->where("id", \request()->get('status_id'))->first();

        if ($t && $t['defines_complete']) {
            $task->is_completed = true;
        } else {
            $task->is_completed = false;
        }

        $task->status_id = \request()->get('status_id');
    }

    $task->update();
    $data['task'] = $task->fresh()->load('worklogs', 'comments');
    $data['status'] = \request()->get("status");
    return $data;
});

Route::get("/api/task/{task}", function (Task $task) {
    $data['task'] = $task->load('worklogs', 'comments');
    $projectId = $task->module->project_id;
    $data['status'] = Status::where(["statusable_type" => "App\Project", "statusable_id" => $projectId])->get();
    return $data;
});

Route::post("/api/task/{task}/comment", function (Task $task, Request $request) {
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
