<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }


    public function test_canCreateACategory()
    {
        $this->refreshTestDatabase();
        $data = [
            "name" => $this->faker->sentence()
        ];
        $response = $this->post('/api/category', $data);
        $response->assertStatus(201);
        $this->assertDatabaseCount('categories', 1);
    }
    public function test_getAllCategories()
    {
        $this->refreshTestDatabase();
        Category::factory(10)->create();
        $response = $this->getJson('/api/category');

        $response->assertStatus(200);
        $response->assertJsonCount(Category::count());
    }
    public function test_canUpdateCategory()
    {
        $category = Category::factory()->create();
        $category->name = "Update testing";
        $response = $this->putJson("/api/category/{$category->id}", ["name" => $category->name]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            "id" => $category->id,
            "name" => $category->name
        ]);
    }
    public function test_canDeleteCategory()
    {
        $category = Category::factory()->create();

        $response = $this->get("/api/category/{$category->id}");
        $response->assertStatus(200);
        $response = $this->deleteJson("/api/category/{$category->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            "id" => $category->id
        ]);
    }
}
