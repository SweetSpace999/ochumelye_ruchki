<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. ПРАВИЛА И СООБЩЕНИЯ ВАЛИДАЦИИ (НА РУССКОМ)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            // Кастомные сообщения об ошибках
            'name.required' => 'Поле ФИО обязательно для заполнения.',
            'email.required' => 'Поле Email обязательно для заполнения.',
            'email.unique' => 'Пользователь с таким Email уже существует.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'phone.required' => 'Укажите номер телефона.',
            'password.required' => 'Введите пароль.',
            'password.confirmed' => 'Введенные пароли не совпадают.',
            'password.min' => 'Пароль должен быть не короче 8 символов.',
        ]);

        // 2. СОЗДАНИЕ ПОЛЬЗОВАТЕЛЯ В БАЗЕ
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            // По умолчанию задаем роль 'visitor'
            'role' => 'visitor',
        ]);

        // 3. АВТОРИЗАЦИЯ И РЕДИРЕКТ
        Auth::login($user);

        // Вместо redirect() используем полный путь к классу Redirect
        return Redirect::route('home')->with('success', 'Регистрация прошла успешно!');
    }
}
