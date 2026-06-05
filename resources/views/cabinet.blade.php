@extends('layouts.master')

@section('body-class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Личный кабинет ведущего</div>
        <div class="row--small grid between">
            
            <div class="content driver-page">
                <!-- Фото ведущего -->
                <div class="driver-page-photo">
                    <img src="{{ asset('img/' . ($user->image ?? 'driver-page.png')) }}">
                </div>	
                
                <!-- ФИО ведущего -->
                <div class="driver-page-name">{{ $user->name }}</div>
                
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    
                    <table class="driver-page-table">
                        <tbody>
                            @forelse($myClasses as $mc)
                                <tr>
                                    <!-- Левая колонка: Дата, Время и Название МК -->
                                    <td style="vertical-align: top; width: 30%;">
                                        <strong>{{ \Carbon\Carbon::parse($mc->date)->format('d.m.Y') }} {{ $mc->time_slot }}ч.</strong>
                                        <br>
                                        <b style="color: #007bff;">{{ $mc->title }}</b>
                                        <br><br>
                                        <a href="{{ route('mc.edit', $mc->id) }}" style="font-size: 11px; color: #666; text-decoration: underline;">Редактировать описание</a>
                                    </td>
                                    
                                    <!-- Правая колонка: Список участников -->
                                    <td>
                                        @if($mc->participants->count() > 0)
                                            @foreach($mc->participants as $index => $participant)
                                                <p>
                                                    {{ $index + 1 }}. {{ $participant->name }}<br>
                                                    email: {{ $participant->email }} <br>
                                                    tel: {{ $participant->phone ?? 'не указан' }}
                                                </p>
                                            @endforeach
                                        @else
                                            <p style="color: #999; font-style: italic;">Участников пока нет</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; padding: 20px;">У вас пока нет созданных мастер-классов</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Кнопка добавления -->
                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('mc.create') }}" style="text-decoration: none;">
                        <div class="driver-page-btn btn">
                            Добавить мастер-класс
                        </div>
                    </a>
                </div>
            </div>

            <!-- Боковое меню (Рубрики) -->
            <ul class="menu">
                @foreach($allCategories as $cat)
                    <li><a href="{{ route('category', $cat->id) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>

        </div>
    </div>	
</div>
@endsection