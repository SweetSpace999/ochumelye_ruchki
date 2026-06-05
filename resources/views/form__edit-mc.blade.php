@extends('layouts.master')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Редактирование мастер-класса</div>
        
        <div class="row--small">
            <!-- Форма отправляет данные методом PUT (для обновления) -->
            <form action="{{ route('mc.update', $mc->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h2>Изменение параметров МК: {{ $mc->title }}</h2>

                @if ($errors->any())
                    <div style="color: red; margin-bottom: 20px;">
                        @foreach ($errors->all() as $error) <div>• {{ $error }}</div> @endforeach
                    </div>
                @endif

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Описание мастер-класса</label>
                    <textarea name="description" required style="width: 100%; height: 120px; padding: 10px; border: 1px solid #ccc;">{{ old('description', $mc->description) }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Стоимость (руб.)</label>
                    <input type="number" name="price" value="{{ old('price', $mc->price) }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc;">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn" style="background: #28a745; color: white; padding: 10px 25px; border: none; cursor: pointer;">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('cabinet') }}" style="margin-left: 15px; color: #666;">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection