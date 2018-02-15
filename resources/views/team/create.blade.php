@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Create Team</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <form method="post" action="{{ route('team.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Team Name</label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           placeholder="e.g. Marketing, Engineering, or Finance"
                                           name="name"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                              id="description"
                                              name="description"
                                              placeholder="Tell what is your team about">

                                    </textarea>
                                </div>

                                <div class="form-group">
                                    <label for="members">Members</label>
                                    <select id="members" name="members[]" class="form-control" multiple>
                                        @foreach($members as $key => $member)
                                            <option value="{{$key}}">{{$member}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Team</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
