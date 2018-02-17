@extends('layouts.app')

@section('content')
<div class="container">
    <p>
        <a class="btn btn-link" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            {{ $project->name }}
        </a>
    </p>

    @if($project->description)
        <div class="collapse mb-2" id="collapseExample">
            <div class="card card-body">
                {{ $project->description }}
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-header">
                    Modules

                    <button
                            data-toggle="collapse" href="#createModule"
                            class="btn btn-primary btn-sm float-right">New</button>

                    <div class="collapse mt-4" id="createModule">
                        <form action="{{ route('module.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <input type="text" name="name" class="form-control" placeholder="Module Name">
                        </form>
                    </div>
                </div>

                <div class="card-body" style="padding:0px">
                    <div class="list-group" id="list-tab" role="tablist">
                        @foreach($project->modules as $index => $module)
                            <a class="list-group-item list-group-item-action {{ $index == 0 ? 'active' : '' }}"
                               data-toggle="list"
                               href="#list-{{ str_slug($module->name) }}">
                                {{ $module->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-9">

            <div class="tab-content" id="nav-tabContent">
                @foreach($project->modules as $index => $module)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                         id="list-{{ str_slug($module->name) }}">
                        <div class="card card-default">
                            <div class="card-header">
                                {{ $module->name }} Tasks
                                @if($module->total_estimated)
                                    - {{ $module->total_estimated }} Hours
                                @endif

                                <button
                                        class="btn btn-primary btn-sm float-right"
                                        data-toggle="modal" data-target="#createTask"
                                        data-modulename="{{$module->name}}"
                                        data-moduleid="{{$module->id}}">
                                    New Task
                                </button>
                            </div>

                            <div class="card-body" style="padding: 0px;">
                                @foreach($module->tasks as $task)
                                    <div class="card mb-2">
                                        <a href="{{route('task.show', $task)}}">
                                        <div class="card-body {{ $task->is_completed ? 'text-white bg-success' :'text-dark' }}">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <p class="card-text">{{ $task->name }}</p>
                                                    <p class="card-text"><small>Last updated {{ $task->updated_at->diffForHumans() }}</small></p>
                                                </div>
                                                <div class="col-md-5">
                                                    <p class="card-text">
                                                        Due Date {{ $task->due_date }}
                                                    </p>
                                                    <p class="card-text">
                                                         {{ $task->assigned ? 'Assigned to '. $task->assigned->email : 'Un Assigned'  }}
                                                    </p>
                                                    <p class="card-text">
                                                        {{ $task->estimated_time ? 'Estimated Time '. $task->estimated_time .' Hrs': ''  }}
                                                    </p>
                                                </div>
                                            </div>


                                        </div>
                                        </a>

                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('task.store') }}">
                    @csrf
                    <input type="hidden" value="0" id="moduleid" name="moduleid">
                    <div class="form-group">
                        <label for="task-name" class="col-form-label">Name</label>
                        <input type="text" class="form-control" id="task-name" name="name" placeholder="What You want to do ?">
                    </div>
                    <div class="form-group">
                        <label for="description-text" class="col-form-label">Description</label>
                        <textarea class="form-control" id="description-text" name="description" placeholder="Describe your task (optional)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="members">Assigned To</label>
                        <select id="members" name="assigned_to" class="form-control">
                            <option value="">Assign to team member</option>
                            @foreach($project->team->members as $member)
                                <option value="{{$member->id}}">{{$member->email}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status_id" class="form-control">

                            @foreach($project->statuses as $status)
                                <option value="{{ $status->id }}" {{ $status->default ? 'selected' :''}}>
                                    {{ $status->name }} {{ $status->defines_complete ? '(completed)' :'' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task-name" class="col-form-label">Due Date</label>
                                <input type="date" class="form-control" id="task-name" name="due_date" placeholder="When You will complete ?">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task-name" class="col-form-label">Estimated Time</label>
                                <input type="number" class="form-control" id="task-name" name="estimated_time" placeholder="How Much Time You need ?">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script>
        $('#createTask').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var modulename = button.data('modulename'); // Extract info from data-* attributes
            var moduleid = button.data('moduleid'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            modal.find('.modal-title').text('Create Task in  ' + modulename + ' Module');
            modal.find('.modal-body #moduleid').val(moduleid);
        })
    </script>
@endsection