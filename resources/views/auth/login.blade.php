@extends('layouts.master')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small" style="max-width: 400px; margin: 0 auto; background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="text-align: center; margin-bottom: 20px;">Вход в клуб</h2>

            <!-- Ошибки -->
            @if ($errors->any())
                <div style="color: red; margin-bottom: 15px; font-size: 14px;">
                    @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label>Электронная почта (Email):</label><br>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Пароль:</label><br>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label><input type="checkbox" name="remember"> Запомнить меня</label>
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 12px; background: #4a6283; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    ВОЙТИ
                </button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; font-size: 14px;">
                Нет аккаунта? <a href="{{ route('register') }}" style="color: #007bff;">Зарегистрироваться</a>
            </p>
        </div>
    </div>
</div>
@endsection