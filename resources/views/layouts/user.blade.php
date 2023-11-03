<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    @vite('resources/js/app.js')
</head>

<body>
    <div id="app" class="d-flex row">
        <div class="col-md-2 main-sidebar d-flex flex-column flex-shrink-0 p-3 text-white ">
            <h3>
                <a href="/"
                    class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <div class="icon">
                        Icon
                    </div>
                    サンプル
                </a>
            </h3>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto row">
                <li class="nav-item">
                    <a class="nav-link text-white  d-flex" data-bs-toggle="collapse" href="#collapse-posted"
                        role="button" aria-expanded="false" aria-controls="collapse-posted">
                        <h4>投稿管理</h4>
                        <div class="dropdown-arrow px-2 down">
                            <i class="bi bi-caret-left-fill p-2" class="arrow-left "></i>
                        </div>
                    </a>
                    <div class="collapse show menu-collapse" id="collapse-posted">
                        <ul>
                            <li class="collapse-item row">
                                <a href="{{ route('user.view') }}" class=" nav-link text-white" aria-current="page">
                                    <h5>一覧</h5>
                                </a>
                            </li>
                            <li class="collapse-item row">
                                <a href="{{ route('user.create') }}" class=" nav-link text-white" aria-current="page">
                                    <h5>作成</h5>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white d-flex " data-bs-toggle="collapse" href="#collapse-analysis"
                        role="button" aria-expanded="false" aria-controls="collapse-analysis">

                        <h4>分析</h4>
                        <div class="dropdown-arrow px-2">
                            <i class="bi bi-caret-left-fill p-2" class="arrow-left"></i>
                        </div>
                    </a>
                    <div class="collapse show menu-collapse" id="collapse-analysis">
                        <ul>
                            <li class="collapse-item row">
                                <a href="{{ route('user.view') }}" class=" nav-link text-white" aria-current="page">
                                    <h5>一覧</h5>
                                </a>
                            </li>
                            <li class="collapse-item row">
                                <a href="{{ route('user.create') }}" class=" nav-link text-white" aria-current="page">
                                    <h5>作成</h5>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex " data-bs-toggle="collapse" href="#collapse-setting"
                        role="button" aria-expanded="false" aria-controls="collapse-setting">
                        <h4> 設定 </h4>
                        <div class="dropdown-arrow px-2">
                            <i class="bi bi-caret-left-fill p-2" class="arrow-left"></i>
                        </div>
                    </a>
                    <div class="collapse show menu-collapse" id="collapse-setting">
                        <ul>
                            <li class="collapse-item row">
                                <a href="{{ route('user.setting.channel') }}" class=" nav-link text-white"
                                    aria-current="page">
                                    <h5>channel</h5>
                                </a>
                            </li>
                            <li class="collapse-item row">
                                <a href="{{ route('user.create') }}" class=" nav-link text-white" aria-current="page">
                                    <h5>作成</h5>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <hr>
        </div>

        <div class="col-md-10 p-0 m-0">
            <div class="nav-bar main-navbar d-flex justify-content-between">
                <div class="dropdown p-2 nav-item ">
                    <div class="nav-item dropdown-toggle d-flex align-items-center" type="button"
                        data-bs-toggle="dropdown" aria="false">
                        <h4>株式会社クライアント</h4>
                    </div>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
                <div class="dropdown p-2 nav-item acount">
                    <div class="nav-item d-flex">
                        <div class="px-2">Avatar</div>
                        <div class="dropdown-toggle d-flex align-items-center" type="button"
                            data-bs-toggle="dropdown" aria="false">

                            <h5 class="px-2">
                                @php
                                    echo Auth::user()->name;
                                @endphp
                            </h5>
                        </div>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">プロフィール</a></li>
                            <li><a class="dropdown-item" href="#">設定</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.logout') }}">ログアウト</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
    @stack('script')
</body>

</html>
