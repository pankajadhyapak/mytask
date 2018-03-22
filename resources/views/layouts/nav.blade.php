<div v-cloak>

<nav-component inline-template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light appbar fixed-top" :class="{ 'bgsame': authUser}">
        <div class="container-fluid">

            @auth
                <router-link
                    to="/"
                    class="navbar-brand">
                    {{ config('app.name', 'Laravel') }}
                </router-link>
            @endauth
            @guest
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            @endguest

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                {{--<ul class="navbar-nav mr-auto">--}}
                    {{--@auth--}}
                        {{--<li>--}}
                            {{--<router-link to="/dashboard" class="nav-link text-primary">Home</router-link>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<router-link to="/dashboard/about" class="nav-link text-primary">About</router-link>--}}
                        {{--</li>--}}

                    {{--@endauth--}}
                {{--</ul>--}}
                @auth
                    <ul class="navbar-nav mr-auto" v-if="showProjectNav.show" style="margin-left: 148px;">

                            <li>
                                <router-link
                                        :to="showProjectNav.currentPath+ '/tasks'"
                                        class="nav-link text-primary">Tasks</router-link>
                            </li>
                            <li>
                                <router-link
                                        :to="showProjectNav.currentPath + '/report'"
                                        class="nav-link text-primary">Report</router-link>
                            </li>
                            {{--<li>--}}
                                {{--<router-link--}}
                                        {{--:to="showProjectNav.currentPath + '/calendar'"--}}
                                        {{--class="nav-link text-primary">Calendar</router-link>--}}
                            {{--</li>--}}
                            <li>
                                <router-link
                                        :to="showProjectNav.currentPath + '/files'"
                                        class="nav-link text-primary">Files</router-link>
                            </li>
                    </ul>
                @endauth

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item"><a class="nav-link text-primary" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link text-primary" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item" v-if="showProjectNav.show">
                            <input
                                    v-model="searchKey"
                                    class="form-control mr-sm-2 nav-search"
                                    type="text"
                                    placeholder="Search your tasks..."
                                    aria-label="Search">
                        </li>
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
                            <ul role="menu" class="dropdown-menu user-nav">
                                <li class="dropdown-header">Settings</li>
                                <li>
                                    <router-link
                                        to="/settings"
                                        class="dropdown-item">
                                        <i class="fa fa-fw fa-btn fa-cog"></i>
                                    Settings</router-link>
                                </li>
                                <li>
                                    <router-link
                                            to="/time-sheet"
                                            class="dropdown-item">
                                        <i class="fa fa-fw fa-btn fa-calendar-o"></i>
                                        Time Sheet
                                    </router-link>
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
</div>
