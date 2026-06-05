<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ОчУмелые ручки</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="@yield('body-class')">
    <div class="header">
        <div class="row grid middle between">
            <!-- ЛОГОТИП ТЕПЕРЬ ССЫЛКА -->
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Логотип">
                </a>
            </div>
            <div class="title">Клуб «ОчУмелые ручки»</div>
            <div class="auth">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf 
                        <button style="background:none; border:none; color:blue; cursor:pointer; font-size:12px;">
                            Выход ({{ Auth::user()->name }})
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Вход</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- БЛОК ВЫВОДА УВЕДОМЛЕНИЙ -->
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; margin-top: 20px; border-radius: 5px; font-weight: bold; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin-top: 20px; border-radius: 5px; font-weight: bold; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                ⚠ {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; margin-top: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <!-- КОНЕЦ БЛОКА УВЕДОМЛЕНИЙ -->

    @yield('content')

    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>
</body>
</html>