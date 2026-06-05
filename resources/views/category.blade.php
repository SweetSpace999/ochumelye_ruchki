@extends('layouts.master')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">{{ $category->name }}</div>
        
        <div class="row--small grid between">
            <div class="content">
                <img src="{{ asset('img/' . $category->image) }}" alt="{{ $category->name }}" style="width: 100%; max-height: 300px; object-fit: cover;">
                
                <!-- ВЫВОДИМ HTML-РАЗМЕТКУ БЕЗ ЭКРАНИРОВАНИЯ (сработают теги <p> и <span>) -->
                <div style="margin-top: 20px; font-size: 16px; line-height: 1.6;">
                    {!! $category->description !!}
                </div>
            </div>

            <ul class="menu">
                @foreach($allCategories as $cat)
                    <li><a href="{{ route('category', $cat->id) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="row shedule">
            <div class="row--small">
                <h2>Расписание</h2>
                <div class="drivers">
                    @forelse($masterClasses as $mc)
                        <div class="driver grid">
                            <div class="driver-left grid">
                                <div class="driver-photo">
                                    <img src="{{ asset('img/driver1.png') }}">
                                </div>
                                <div class="driver-text">
                                    <div class="driver-name">{{ $mc->instructor->name }}</div>
                                    <div class="driver-desc"><b>{{ $mc->title }}</b>: {{ $mc->description }}</div>
                                    <div class="driver-desc">Свободно мест: {{ $mc->capacity - $mc->participants->count() }}</div>
                                </div>
                            </div>
                            <div class="driver-right">
                                @auth
                                    @if(Auth::user()->role === 'visitor')
                                        <form action="{{ route('book.store', $mc->id) }}" method="POST">
                                            @csrf
                                            <button class="driver-btn" style="cursor: pointer;">записаться</button>
                                        </form>
                                    @endif
                                @endauth
                                <div class="driver-time">{{ \Carbon\Carbon::parse($mc->date)->format('d.m') }} в {{ $mc->time_slot }}ч.</div>
                            </div>	
                        </div>
                    @empty
                        <p style="color: #fff; padding: 20px;">На данный момент занятий не запланировано.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>	
</div>
@endsection