@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            @include("dashboard.nav")
            <div class="col-md-9">
                <router-view>

                </router-view>
            </div>
        </div>
    </div>
@endsection
