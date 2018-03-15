@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            @include("dashboard.nav")
            <div class="col-md-10">
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </div>
        </div>
    </div>
@endsection
