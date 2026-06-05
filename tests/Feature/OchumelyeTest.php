<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
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
}
