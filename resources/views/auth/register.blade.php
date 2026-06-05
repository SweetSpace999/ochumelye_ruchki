@extends('layouts.master')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small" style="max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="text-align: center; margin-bottom: 20px;">Регистрация нового участника</h2>

           @if ($errors->any())
            <div style="color: red; margin-bottom: 15px; font-size: 14px; border: 1px solid red; padding: 10px; background: #fff3f3;">
                <ul style="margin: 0;">
                    @foreach ($errors->all() as $error) 
                        <li>{{ $error }}</li> 
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label>ФИО:</label><br>
                    <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Email:</label><br>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Номер телефона:</label><br>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+7 (___) ___ __ __" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Пароль:</label><br>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label>Подтвердите пароль:</label><br>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    ЗАРЕГИСТРИРОВАТЬСЯ
                </button>
            </form>
        </div>
    </div>
</div>
@endsection