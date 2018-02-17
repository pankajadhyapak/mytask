@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('project.show', $task->module->project) }}">{{ $task->module->project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('project.show', $task->module->project) }}">{{ $task->module->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$task->name}}</li>
                    </ol>

                </nav>
            </div>
            <div class="col-md-6">
                <div class="progress mt-3">
                    <div
                            class="progress-bar progress-bar-striped progress-bar-animated"
                            role="progressbar"
                            aria-valuenow="{{($task->worklogs->sum("hours")/$task->estimated_time) * 100}}"
                            aria-valuemin="0"
                            aria-valuemax="{{$task->estimated_time}}"
                            style="width: {{($task->worklogs->sum("hours")/$task->estimated_time) * 100}}%">
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header {{ $task->is_completed ? 'bg-success' : '' }}">{{ $task->name }}</div>

                    <div class="card-body">
                        <h5>Description</h5>
                        <p>
                            {{$task->description}}
                        </p>
                        <hr>
                        {{--<small>--}}
                        {{--<p>--}}
                            {{--<strong>created by </strong>{{$task->owner->display_name}} ({{$task->owner->email}})--}}
                        {{--</p>--}}
                        {{--<p>--}}
                            {{--<strong>Module </strong>{{$task->module->name}}--}}
                        {{--</p>--}}
                        {{--<p>--}}
                            {{--<strong>Project</strong> {{$task->module->project->name}}--}}
                        {{--</p>--}}
                        {{--</small>--}}
                        {{--<hr>--}}

                        <h5>Tags</h5>
                        <p>
                            Coming Soon...
                        </p>
                        <hr>
                        <div>
                            <h5>Activity</h5>

                            <div class="add-comment">
                                <form action="{{ route('comment.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="type" value="Task">
                                    <input type="hidden" name="type_id" value="{{$task->id}}">
                                    <div class="form-group">
                                        <textarea name="body" class="form-control" placeholder="Write Your comment here...">
                                        </textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Add Comment</button>
                                    </div>
                                </form>
                            </div>

                            <div class="comment-list">
                                @foreach($task->comments as $comment)
                                    <div class="comment-item mb-4">
                                    <div class="avatar">
                                        {{$comment->owner->display_name[0]}}
                                    </div>
                                    <div>
                                        <div class="comment-meta">
                                            <span class="comment-author text-dark">{{ $comment->owner->display_name }}</span>
                                            <small><span class="comment-date text-muted">{{ $comment->created_at->diffForHumans() }}</span></small>
                                        </div>
                                        <div class="comment-body text-secondary">
                                            {{ $comment->body }}
                                        </div>
                                    </div>

                                </div>
                                @endforeach

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header {{ $task->is_completed ? 'bg-success' : '' }}">Details</div>

                    <div class="card-body">
                        <h5>Assigned to</h5>
                        <p>
                            <span class="avatar">{{$task->assigned->display_name[0]}}</span>
                            {{$task->assigned->display_name}} ({{$task->assigned->email}})
                        </p>
                        <hr>
                        <h5>Status</h5>
                        <p>
                            {{$task->status->name}}
                        </p>
                        <hr>
                        <h5>Due Date</h5>
                        <p>
                            {{$task->due_date}}
                        </p>
                        <hr>
                        <h5>Estimated Time</h5>
                        <p>
                            {{$task->estimated_time}} Hours
                        </p>
                        <hr>
                        <h5 class="mb-3">
                            Work Log
                            <button
                                    data-toggle="modal" data-target="#createLogModal"
                                    class="btn btn-primary btn-sm float-right">Log</button>
                        </h5>
                        @foreach($task->worklogs as $log)
                                <div class="log-list mb-3">
                                    <div class="avatar">{{$log->owner->display_name[0]}}</div>
                                    <span>{{$log->owner->display_name}} logged</span>
                                    <strong class="text-success">{{ $log->hours }} Hrs </strong>
                                    on <strong>{{$log->date->format('d M')}}</strong>
                                </div>
                        @endforeach
                        <hr>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="createLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log Work </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('worklog.store') }}">
                        @csrf
                        <input type="hidden" name="task_id" value="{{$task->id}}">
                        <div class="form-group">
                            <label for="timespent">Time Spent</label>
                            <input type="number" class="form-control" id="timespent" name="hours" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <textarea name="comment" class="form-control" id="comments" placeholder="Describe Your work (Optional)">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Log</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
