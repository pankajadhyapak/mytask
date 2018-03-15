@extends('layouts.app')
@section('extra-css')
    <style>
        body{
            background: linear-gradient(to top right, #69489c 10%, #69489c 65%, rgba(56, 52, 40, 0.9) 125%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100vh;
        }
        .appbar{
            background-color: transparent !important;
        }
        .appbar .navbar-brand {
            color: #fff;
        }
        .navbar-light .navbar-nav .nav-link {
            color: #fff !important;
        }
    </style>

@endsection
@section('content')
    <div class="container bg-full">
        <div class="text-center text-white">
            <h1 class="mt-5">
                My Task App
            </h1>
            <h4 class="mt-5">Mini Project Management System</h4>
        </div>
    </div>
@endsection
