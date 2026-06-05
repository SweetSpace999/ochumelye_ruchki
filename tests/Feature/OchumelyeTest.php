<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OchumelyeTest extends TestCase
{
    use RefreshDatabase;

    /** 1. Тест: Главная страница открывается */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** 2. Тест: Страница категории открывается */
    public function test_category_page_returns_successful_response(): void
    {
        $category = Category::create([
            'name' => 'Тест Категория',
            'description' => 'Описание',
            'image' => 'elifant.png'
        ]);

        $response = $this->get('/category/' . $category->id);
        $response->assertStatus(200);
    }

    /** 3. Тест: Неавторизованный пользователь не может войти в кабинет */
    public function test_unauthenticated_user_cannot_access_cabinet(): void
    {
        $response = $this->get('/cabinet');
        $response->assertStatus(302); // Должен быть редирект на логин
    }
}