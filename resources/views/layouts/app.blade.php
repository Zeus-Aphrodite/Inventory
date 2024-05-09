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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- auto fill adress when I type the post code --}}
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css')}}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container d-flex flex-col align-items-between">
                @if (Auth::user() && Auth::user()->name)
                    <div class="flex-fill d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
                        <div class="col-md-3 mb-md-0">
                            <a href="/orders" class="d-inline-flex link-body-emphasis text-decoration-none d-lg-block d-sm-none">
                                イシダ印刷 在庫管理
                            </a>
                        </div>

                        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                            @if (Auth::user() && Auth::user()->user_role == 1)
                                <li><a href="/orders" class="nav-link px-2" href="#">受注管理</a></li>
                                <li><a href="/goods" class="nav-link px-2">商品管理</a></li>
                                <li><a class="nav-link px-2" href="/members">会員管理</a></li>
                                <li><a href="/destination" class="nav-link px-2">発送先管理</a></li>
                                @endif
                            @if (Auth::user() && Auth::user()->user_role == 2)
                                <li><a href="/orders" class="nav-link px-2" href="#">受注一覧</a></li>
                                <li><a href="/destination" class="nav-link px-2">発送先一覧</a></li>
                            @endif
                            @if (Auth::user() && Auth::user()->user_role == 3)
                                <li><a href="/orders" class="nav-link px-2">依頼一覧</a></li>
                                <li><a href="/destination" class="nav-link px-2">発送先管理</a></li>
                                <li><a class="nav-link px-2" href="/edit-member-infor">会員情報編集</a></li>
                            @endif
                            {{-- <li><a href="#" class="nav-link px-2">過去の依頼履歴</a></li> --}}
                        </ul>
                    </div>
                @endif
                <div class="flex-fill collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary me-2" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                                </li>
                            @endif

                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('register') }}">{{ __('登録する') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item">
                                <form action="POST" class="nav-link btn me-2" href="/" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </form>
                                
                                <li class="nav-item">
                                    <a class="btn me-2" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('ログアウト') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
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
    <script src="{{ asset('assets/js/members.js')}}"></script>
    <script src="{{ asset('assets/js/goods.js')}}"></script>
    <script src="{{ asset('assets/js/destinations.js')}}"></script>
    <script src="{{ asset('assets/js/orders.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
