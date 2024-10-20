<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">

            {{-- notification --}}
            @if (session('notification'))
                <div class="alert alert-info">
                    {{ session('notification') }}
                </div>
            @endif






            <div class="container">
                <a class="navbar-brand" href="{{ url('/posts') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                @guest
                @else
                    <a class="navbar-brand" href="{{ url('/posts') }}">
                        Posts
                    </a>
                    @if (Auth::user()->hasRole('Admin'))
                        <a class="navbar-brand" href="{{ url('/users') }}">
                            Users
                        </a>
                    @endif
                @endguest

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="notificationDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Notifications <span
                                        class="badge badge-light">{{ auth()->user()->unreadNotifications->count() }}</span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                                    @forelse (auth()->user()->unreadNotifications as $notification)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                {{ $notification->data['title'] }}
                                            </button>
                                        </form>
                                        <div class="dropdown-divider"></div>
                                    @empty
                                        <a class="dropdown-item" href="#">No new notifications</a>
                                    @endforelse
                                </div>
                            </div>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
