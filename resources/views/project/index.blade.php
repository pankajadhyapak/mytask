@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">All Projects</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($projects as $project)
                            <h3>
                                <a href="{{ route('project.show', $project) }}">{{ $project->name }}</a>
                                <span class="badge badge-primary">{{ $project->team->name }}</span>
                            </h3>

                            <hr>
                        @endforeach
                    </div>
                </div>

                <br>
                {{$projects->links()}}
            </div>
        </div>
    </div>
@endsection
