@extends('layouts.master')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Новый мастер-класс</div>
        
        <div class="row--small" style="padding-top: 250px; position: relative; z-index: 10;">
            
            <form action="{{ route('mc.store') }}" method="POST" style="background: rgba(255,255,255,0.9); padding: 20px; border-radius: 8px;">
                @csrf
                
                <h2 style="margin-bottom: 20px;">Заполните данные занятия</h2>

                @if ($errors->any())
                    <div style="color: red; margin-bottom: 20px; background: #fff1f1; padding: 10px; border: 1px solid red;">
                        @foreach ($errors->all() as $error) <div>• {{ $error }}</div> @endforeach
                    </div>
                @endif

                <!-- Поле выбора категории (Вид творчества) -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Вид творчества:</label><br>
                    <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #4a6283;">
                        <option value="">-- Выберите направление --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Название мастер-класса:</label>
                    <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 10px; border: 1px solid #4a6283;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Описание:</label>
                    <textarea name="description" required style="width: 100%; height: 80px; padding: 10px; border: 1px solid #4a6283;">{{ old('description') }}</textarea>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="font-weight: bold;">Дата:</label>
                        <input type="date" name="date" id="dateInput" min="{{ date('Y-m-d') }}" required style="width: 100%; padding: 10px; border: 1px solid #4a6283;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-weight: bold;">Время (Сетка):</label>
                        <select name="time_slot" id="timeSlotInput" required style="width: 100%; padding: 10px; border: 1px solid #4a6283;">
                            <option value="">-- Выберите дату --</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}ч.</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 25px;">
                    <div style="flex: 1;">
                        <label style="font-weight: bold;">Кол-во человек:</label>
                        <input type="number" name="capacity" min="1" required style="width: 100%; padding: 10px; border: 1px solid #4a6283;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-weight: bold;">Стоимость (руб):</label>
                        <input type="number" name="price" min="0" required style="width: 100%; padding: 10px; border: 1px solid #4a6283;">
                    </div>
                </div>

                <button type="submit" class="btn" style="background: #4a6283; color: white; padding: 15px; border: none; cursor: pointer; width: 100%; font-weight: bold;">
                    ОПУБЛИКОВАТЬ В РАСПИСАНИЕ
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const busySlots = {!! json_encode($busySlots) !!};
    const dateInput = document.getElementById('dateInput');
    const timeSelect = document.getElementById('timeSlotInput');
    const originalOptions = Array.from(timeSelect.options);

    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        originalOptions.forEach(opt => {
            if(opt.value === "") return;
            const isBusy = busySlots.includes(selectedDate + '|' + opt.value);
            opt.disabled = isBusy;
            opt.style.color = isBusy ? 'red' : 'black';
            opt.text = isBusy ? opt.value + ' (Занято вами)' : opt.value + 'ч.';
        });
    });
</script>
@endsection