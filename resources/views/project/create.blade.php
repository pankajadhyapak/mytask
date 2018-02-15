@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Create Project</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('project.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Project Name</label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           placeholder="Enter your project name"
                                           name="name"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                              id="description"
                                              name="description"
                                              placeholder="Tell what is your project about">

                                    </textarea>
                                </div>

                                <div class="form-group">
                                    <label for="members">Team</label>
                                    <select id="members" name="team" class="form-control" required>
                                            <option value="">Select Team</option>
                                        @foreach($teams as $team)
                                            <option value="{{$team->id}}">{{$team->name}} - {{$team->members_count}} Members</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Project</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
