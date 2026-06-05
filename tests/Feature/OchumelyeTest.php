<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OchumelyeTest extends TestCase
{
    use RefreshDatabase;

    /** 1. Тест: Главная страница */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** 2. Тест: Страница категории */
    public function test_category_page_returns_successful_response(): void
    {
        $category = Category::create([
            'name' => 'Тест Категория',
            'description' => 'Описание',
            'image' => 'elifant.png',
        ]);

        $response = $this->get('/category/'.$category->id);
        $response->assertStatus(200);
    }

    /** 3. Тест: Неавторизованный пользователь не может войти в кабинет */
    public function test_unauthenticated_user_cannot_access_cabinet(): void
    {
        $response = $this->get('/cabinet');
        $response->assertStatus(302); // Редирект на логин
    }

    /** 4. Тест: Ведущий может зайти в личный кабинет */
    public function test_lead_user_can_access_cabinet(): void
    {
        $lead = User::create([
            'name' => 'Ольга Мастер',
            'email' => 'lead@test.com',
            'password' => 'password',
            'phone' => '1234567',
            'role' => 'lead',
        ]);

        $response = $this->actingAs($lead)->get('/cabinet');
        $response->assertStatus(200);
    }

    /** 5. Тест: Ведущий может открыть форму создания МК */
    public function test_lead_user_can_access_create_form(): void
    {
        $lead = User::create([
            'name' => 'Ольга Мастер',
            'email' => 'lead@test.com',
            'password' => 'password',
            'phone' => '1234567',
            'role' => 'lead',
        ]);

        $response = $this->actingAs($lead)->get('/cabinet/create');
        $response->assertStatus(200);
    }

    /** 6. Тест: Ведущий может успешно СОЗДАТЬ мастер-класс (POST) */
    public function test_lead_user_can_store_master_class(): void
    {
        $lead = User::create([
            'name' => 'Ольга Мастер',
            'email' => 'lead@test.com',
            'password' => 'password',
            'phone' => '1234567',
            'role' => 'lead',
        ]);

        $category = Category::create([
            'name' => 'Кулинария',
            'description' => 'Описание',
            'image' => 'elifant.png',
        ]);

        $response = $this->actingAs($lead)->post('/cabinet/store', [
            'category_id' => $category->id,
            'title' => 'Шоколадный торт',
            'description' => 'Учимся печь',
            'date' => '2026-05-20',
            'time_slot' => '11-13',
            'capacity' => 10,
            'price' => 1500,
        ]);

        $response->assertRedirect('/cabinet');
        $this->assertDatabaseHas('master_classes', ['title' => 'Шоколадный торт']);
    }

    /** 7. Тест: Посетитель видит форму подтверждения записи */
    public function test_visitor_can_see_booking_confirmation_page(): void
    {
        $visitor = User::create(['name' => 'Иван', 'email' => 'visitor@test.com', 'password' => 'pass', 'role' => 'visitor']);
        $lead = User::create(['name' => 'Мастер', 'email' => 'lead@test.com', 'password' => 'pass', 'role' => 'lead']);
        $category = Category::create(['name' => 'Кулинария', 'description' => 'Тест', 'image' => 'elifant.png']);

        $mc = MasterClass::create([
            'category_id' => $category->id,
            'user_id' => $lead->id,
            'title' => 'Стейки',
            'description' => 'Текст',
            'date' => '2026-05-20',
            'time_slot' => '11-13',
            'capacity' => 10,
            'price' => 2000,
        ]);

        // ИСПРАВЛЕНО: путь /book/confirm/ вместо /booking/confirm/
        $response = $this->actingAs($visitor)->get("/book/confirm/{$mc->id}");
        $response->assertStatus(200)->assertSee('Стейки');
    }

    /** 8. Тест: Посетитель может успешно записаться на мастер-класс */
    public function test_visitor_can_store_booking(): void
    {
        $visitor = User::create(['name' => 'Иван', 'email' => 'visitor@test.com', 'password' => 'pass', 'role' => 'visitor']);
        $lead = User::create(['name' => 'Мастер', 'email' => 'lead@test.com', 'password' => 'pass', 'role' => 'lead']);
        $category = Category::create(['name' => 'Кулинария', 'description' => 'Тест', 'image' => 'elifant.png']);

        $mc = MasterClass::create([
            'category_id' => $category->id,
            'user_id' => $lead->id,
            'title' => 'Стейки',
            'description' => 'Текст',
            'date' => '2026-05-20',
            'time_slot' => '11-13',
            'capacity' => 10,
            'price' => 2000,
        ]);

        // ИСПРАВЛЕНО: путь /book/store/ вместо /booking/store/
        $response = $this->actingAs($visitor)->post("/book/store/{$mc->id}");

        $response->assertRedirect("/category/{$category->id}");
        $this->assertDatabaseHas('bookings', [
            'user_id' => $visitor->id,
            'master_class_id' => $mc->id,
        ]);
    }
}
