<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema; // Добавили для фикса ошибки

class MainController extends Controller
{
    public function __construct()
    {
        if (Schema::hasTable('categories')) {
            view()->share('allCategories', Category::all());
        }
    }

    public function index()
    {
        $categories = Category::all();
        $myBookings = [];

        /** @var User $user */
        $user = Auth::user();

        if ($user && $user->role === 'visitor') {
            $myBookings = $user->bookings()->with('instructor', 'category')->get();
        }

        // Убедись, что файл resources/views/index.blade.php существует!
        return view('index', compact('categories', 'myBookings'));
    }

    public function category($id)
    {
        $category = Category::findOrFail($id);
        $masterClasses = MasterClass::where('category_id', $id)->with('instructor', 'participants')->get();

        return view('category', compact('category', 'masterClasses'));
    }

    public function cabinet()
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user || $user->role !== 'lead') {
            abort(403);
        }
        $myClasses = MasterClass::where('user_id', $user->id)->with('participants')->get();

        return view('cabinet', compact('myClasses', 'user'));
    }

    // Показ формы
    public function createMC()
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user || $user->role !== 'lead') {
            abort(403);
        }

        $categories = Category::all();
        $timeSlots = ['9-11', '11-13', '13-15', '15-17'];

        // ПУНКТ ТЗ 1.5: Передаем занятые слоты
        $busySlots = MasterClass::where('user_id', $user->id)
            ->get(['date', 'time_slot'])
            ->map(fn ($i) => $i->date.'|'.$i->time_slot)->toArray();

        return view('form_master_class', compact('categories', 'timeSlots', 'busySlots'));
    }

    // Сохранение в БД
    public function storeMC(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        $data['user_id'] = Auth::id();
        MasterClass::create($data);

        // ИСПРАВЛЕНО: Редирект в кабинет (cabinet), а не на главную!
        return Redirect::route('cabinet')
            ->with('success', 'Мастер-класс успешно добавлен в ваше расписание!');
    }

    public function confirmBooking($id)
    {
        $mc = MasterClass::with(['category', 'instructor'])->findOrFail($id);

        return view('confirm', compact('mc'));
    }

    public function storeBooking($id)
    {
        $mc = MasterClass::findOrFail($id);
        /** @var User $user */
        $user = Auth::user();

        // 1. ПРОВЕРКА: Наличие свободных мест (Пункт 2.10 ТЗ)
        // Считаем сколько людей уже записано и сравниваем с вместимостью
        if ($mc->participants()->count() >= $mc->capacity) {
            return Redirect::route('category', $mc->category_id)
                ->with('error', 'К сожалению, на этот мастер-класс больше нет свободных мест.');
        }

        // 2. ПРОВЕРКА: Нельзя записаться дважды на ОДИН И ТОТ ЖЕ мастер-класс
        if ($user->bookings()->where('master_class_id', $id)->exists()) {
            return Redirect::route('category', $mc->category_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс.');
        }

        // 3. ПРОВЕРКА: Нельзя записаться на другое занятие в ТО ЖЕ время и дату (Пункт 2.9 ТЗ)
        // Ищем среди всех записей пользователя те, у которых дата и время совпадают с текущим МК
        $isTimeBusy = $user->bookings()
            ->where('date', $mc->date)
            ->where('time_slot', $mc->time_slot)
            ->exists();

        if ($isTimeBusy) {
            return Redirect::route('category', $mc->category_id)
                ->with('error', 'В это время ('.$mc->time_slot.'ч.) вы уже посещаете другой мастер-класс!');
        }

        // Если все проверки пройдены - записываем
        $mc->participants()->attach($user->id);

        return Redirect::route('category', $mc->category_id)
            ->with('success', 'Поздравляем! Вы успешно записаны на занятие.');
    }

    // Форма редактирования (Только описание и стоимость по ТЗ)
    public function editMC($id)
    {
        $mc = MasterClass::findOrFail($id);

        // Проверка: может редактировать только автор
        if ($mc->user_id !== Auth::id()) {
            abort(403);
        }

        return view('form__edit-mc', compact('mc'));
    }

    // Сохранение изменений
    public function updateMC(Request $request, $id)
    {
        $mc = MasterClass::findOrFail($id);

        if ($mc->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
        ]);

        $mc->update($data);

        return Redirect::route('cabinet')->with('success', 'Мастер-класс успешно обновлен!');
    }
}
