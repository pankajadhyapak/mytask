<nav-component inline-template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light appbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @auth
                        <li>
                            <router-link to="/dashboard" class="nav-link text-primary">Home</router-link>
                        </li>
                        <li>
                            <router-link to="/dashboard/about" class="nav-link text-primary">About</router-link>
                        </li>
                    @endauth
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item"><a class="nav-link text-primary" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link text-primary" href="{{ route('register') }}">Register</a></li>
                    @else

                        <li class="nav-item">
                            <a class="nav-link has-activity-indicator">
                                <i class="navbar-icon icon fa fa-bell">

                                </i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="avatar">
                                    {{ auth()->user()->display_name[0] }}
                                </span>
                                 <span class="caret"></span>
                            </a>
                            <ul role="menu" class="dropdown-menu">
                                <li class="dropdown-header">Settings</li>
                                <li>
                                    <a class="dropdown-item" href="/settings">
                                        <i class="fa fa-fw fa-btn fa-cog"></i>Your Settings
                                    </a>
                                </li>
                                {{--<li class="dropdown-divider"></li>--}}
                                {{--<li class="dropdown-header">Support</li>--}}
                                {{--<li>--}}
                                    {{--<a class="dropdown-item" style="cursor: pointer;">--}}
                                        {{--<i class="fa fa-fw fa-btn fa-paper-plane"></i>Email Us--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-fw fa-btn fa-sign-out"></i>Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</nav-component>