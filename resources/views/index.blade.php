@extends('layouts.master')

@section('content')
<!-- ВЕРХНЯЯ ЧАСТЬ: ВИДЫ ТВОРЧЕСТВА (БЕЛЫЙ ФОН) -->
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Наши виды творчества</div>
        
        <div class="row--small grid between">
            <div class="content">
                @foreach($categories as $cat)
                    <div style="margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px dashed #ccc;">
                        <div style="float: left; margin-right: 20px; margin-bottom: 10px;">
                            <img src="{{ asset('img/' . $cat->image) }}" alt="{{ $cat->name }}" style="max-width: 250px; border-radius: 5px; box-shadow: 0 0 5px rgba(0,0,0,0.2);">
                        </div>
                        <h2 style="margin-top: 0;">
                            <a href="{{ route('category', $cat->id) }}" style="color: #007bff; text-decoration: none;">
                                {{ $cat->name }}
                            </a>
                        </h2>
                        <div style="text-align: justify; line-height: 1.6; color: #444;">
                            {{ Str::limit(strip_tags($cat->description), 150) }}
                        </div>
                        <div style="clear: both;"></div> 
                    </div>
                @endforeach
            </div>

            <ul class="menu">
                @foreach($allCategories as $cat)
                    <li><a href="{{ route('category', $cat->id) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>	
</div>

<!-- НИЖНЯЯ ЧАСТЬ: РАСПИСАНИЕ (ТЕМНО-СИНИЙ ФОН НА ВСЮ ШИРИНУ) -->
@if(Auth::check() && Auth::user()->role === 'visitor')
    <!-- Используем !important чтобы перебить любой CSS из styles.css -->
    <div style="background-color: #2c3e50 !important; width: 100% !important; padding: 50px 0 !important; margin-top: 40px !important;">
        <div class="row">
            <div class="row--small">
                
                <h1 style="color: #ffffff !important; margin-bottom: 40px !important; font-size: 32px !important;">Мое расписание мастер-классов</h1>
                
                <div class="drivers">
                    @forelse($myBookings as $booking)
                        <!-- БЕЛАЯ КАРТОЧКА ВНУТРИ ТЕМНОГО ФОНА -->
                        <div class="driver grid" style="margin-bottom: 20px !important; background-color: #ffffff !important; padding: 25px !important; border-radius: 8px !important; box-shadow: 0 5px 15px rgba(0,0,0,0.3) !important;">
                            
                            <div class="driver-left grid">
                                <div class="driver-photo">
                                    <img src="{{ asset('img/driver1.png') }}" style="border-radius: 50%;">
                                </div>
                                <div class="driver-text">
                                    <div class="driver-name" style="color: #2c3e50 !important; font-size: 20px !important; font-weight: bold !important; margin-bottom: 8px !important;">
                                        {{ $booking->title }}
                                    </div>
                                    <div class="driver-desc" style="color: #555 !important; font-size: 15px !important; line-height: 1.6 !important;">
                                        <strong style="color: #000;">Категория:</strong> {{ $booking->category->name }} <br>
                                        <strong style="color: #000;">Ведущий:</strong> {{ $booking->instructor->name }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="driver-right" style="text-align: right; display: flex; flex-direction: column; justify-content: center;">
                                <div class="driver-time" style="font-size: 24px !important; color: #28a745 !important; font-weight: bold !important;">
                                    {{ \Carbon\Carbon::parse($booking->date)->format('d.m.Y') }} <br>
                                    <span style="font-size: 18px !important; color: #666 !important;">{{ $booking->time_slot }}ч.</span>
                                </div>
                            </div>	
                            
                        </div>
                    @empty
                        <div style="background-color: #34495e !important; padding: 20px !important; border-radius: 5px !important; border-left: 5px solid #3498db !important;">
                            <p style="color: #fff !important; font-size: 18px !important; margin: 0 !important;">
                                Вы пока не записаны ни на один мастер-класс.
                            </p>
                        </div>
                    @endforelse
                </div>
                
            </div>
        </div>
    </div>
@endif
@endsection