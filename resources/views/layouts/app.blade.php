<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="nav has-shadow">
            <div class="nav-left">
                <a class="nav-item">
                    <img src="http://bulma.io/images/bulma-logo.png" alt="Bulma logo">
                </a>
            </div>

            <span class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <div class="nav-right nav-menu is-hidden-tablet">

                @if (Auth::guest())
                    <a class="nav-item" href="#">
                        Login
                    </a>
                    <a class="nav-item" href="#">
                        Register
                    </a>
                @else                    
                    <a href="#"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Logout
                    </a>                            
                @endif
            </div>
            <div class="nav-right nav-menu">
                @if (Auth::guest())
                    <a class="nav-item" href="#">
                        Login
                    </a>
                    <a class="nav-item" href="#">
                        Register
                    </a>
                @else
                    <dropdown trigger="hover">
                        <a class="button is-white">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="icon is-small">
                                <i class="fa fa-angle-down"></i>
                            </span>
                        </a>
                        <div slot="content">
                            <a href="#"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="#" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </dropdown>
                @endif
            </div>            
        </nav>
        <sidebar>
            <ul>
                <li>
                    <router-link to="/home" >
                        <i class="fa fa-book fa-2x"></i>
                        <span class="nav-text">Home</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/estados" >
                        <i class="fa fa-briefcase fa-2x"></i>
                        <span class="nav-text">Estados</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/delitos" >
                        <i class="fa fa-balance-scale fa-2x"></i>
                        <span class="nav-text">Delitos</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/registro" >
                        <i class="fa fa-book fa-2x"></i>
                        <span class="nav-text">Registros</span>
                    </router-link>
                </li>
                <li>
                    <router-link to="/about" >
                        <i class="fa fa-book fa-2x"></i>
                        <span class="nav-text">About</span>
                    </router-link>
                </li>
            </ul>
        </sidebar>
        <section class="section">
            <div class="container">
                <router-view></router-view>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script>
        (function() {
            var burger = document.querySelector('.nav-toggle');
            var menu = document.querySelector('.nav-menu');
            burger.addEventListener('click', function() {
                burger.classList.toggle('is-active');
                menu.classList.toggle('is-active');
            });
        })();
    </script>
</body>
</html>
