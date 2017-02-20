<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
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
        <navbar>
            <template slot="hidden">
                <a href="{{ route('logout') }}" 
                    class="nav-item is-hidden-tablet"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                </form>
            </template>
            <template slot="logout">
                <a href="{{ route('logout') }}" class="nav-item is-tab is-hidden-mobile"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>
            </template>
        </navbar>
        <sidebar> 
            <template slot="logout">
                <ul class="logout">
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off fa-2x"></i>
                            <span class="nav-text">
                                Logout
                            </span>
                        </a>
                    </li>  
                </ul>
            </template>
        </sidebar>
        <section class="section">
            <div class="container">
            <transition
                name="fade"
                enter-active-class="fadeIn"
                leave-active-class="fadeOut"
                >
                <router-view></router-view>
            </transition>
            </div>
        </section>
    </div>
<footer class="footer">
  <div class="container">
    <div class="content has-text-centered">
      <p>
        <strong>Sistema de Control de Registros</strong> por Abel Barrientos.
      </p>
      <p>
        <a class="icon" href="https://github.com/sphinix27/registros">
          <i class="fa fa-github"></i>
        </a>
      </p>
    </div>
  </div>
</footer>
    <!-- Scripts -->
    <script src="js/app.js"></script>
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
