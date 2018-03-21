@extends('layouts.app')
@section('extra-css')
    <style>
        body{
            background: #3b3c74;
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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center text-white">
                <h2>Sign In </h2>
                <p>welcome back</p>
            </div>
            <div class="card card-default">


                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="col-form-label text-md-right">E-Mail Address</label>

                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">Password</label>
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                                </div>
                                <div class="col-md-6" style="text-align: right;">
                                    <a href="{{ route('password.request') }}" >
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                    Login
                                </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
