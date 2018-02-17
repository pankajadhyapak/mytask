<?php

namespace App\Http\Controllers;

use App\Task;
use App\WorkLog;
use Illuminate\Http\Request;

class WorkLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO change to custom type later

        $task = Task::findOrFail($request->get('task_id'));

        $task->worklogs()->create([
            'user_id' => auth()->id(),
            'comment' => $request->get('comment'),
            'hours' => $request->get('hours'),
            'date' => $request->get('date')
        ]);

        return redirect()->back()->with("status", "Logged Work Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkLog $workLog
     * @return \Illuminate\Http\Response
     */
    public function show(WorkLog $workLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkLog $workLog
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkLog $workLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\WorkLog $workLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkLog $workLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkLog $workLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkLog $workLog)
    {
        //
    }
}
