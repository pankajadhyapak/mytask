@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">All Teams</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach($teams as $team)
                        <h3>{{ $team->name }}</h3>

                        <h6>Members - {{ count($team->members) }}</h6>
                        @foreach($team->members as $member)
                                <span class="badge badge-secondary">{{ $member->email }}</span>
                        @endforeach


                        <h6 class="mt-3">Projects - {{ count($team->projects) }}</h6>
                        @foreach($team->projects as $project)
                            <span class="badge badge-primary">{{ $project->name }}</span>
                        @endforeach
                            <hr>
                    @endforeach
                </div>
            </div>
            <br>
            {{$teams->links()}}
        </div>
    </div>
</div>
@endsection
