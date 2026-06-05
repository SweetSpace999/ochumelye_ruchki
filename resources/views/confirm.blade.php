@extends('layouts.master')
@section('content')
<div class="main">
    <div class="row">
        <div class="row--small" style="text-align:center; padding:50px;">
            <h2>Подтверждение записи</h2>
            <p>Вы записываетесь на <b>{{ $mc->title }}</b></p>
            <p>Мастер: {{ $mc->instructor->name }}</p>
            <p>Дата: {{ $mc->date }} ({{ $mc->time_slot }}ч.)</p>
            <form action="{{ route('book.store', $mc->id) }}" method="POST">
                @csrf
                <button class="btn" style="background:green; color:white; padding:10px 30px;">ПОДТВЕРДИТЬ</button>
            </form>
            <br>
            <a href="{{ route('category', $mc->category_id) }}">Отмена</a>
        </div>
    </div>
</div>
@endsection