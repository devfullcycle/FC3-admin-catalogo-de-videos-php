<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class CategoryApiTest extends TestCase
{
    use WithoutMiddlewareTrait;

    protected $endpoint = '/api/categories';

    public function test_list_empty_categories()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_list_all_categories()
    {
        Category::factory()->count(30)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from'
            ]
        ]);
        $response->assertJsonCount(15, 'data');
    }

    public function test_list_paginate_categories()
    {
        Category::factory()->count(25)->create();

        $response = $this->getJson("$this->endpoint?page=2");
        
        $response->assertStatus(200);
        $this->assertEquals(2, $response['meta']['current_page']);
        $this->assertEquals(25, $response['meta']['total']);
        $response->assertJsonCount(10, 'data');
    }

    public function test_list_category_notfound()
    {
        $response = $this->getJson("$this->endpoint/fake_value");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_list_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("$this->endpoint/{$category->id}");
        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ]
        ]);
        $this->assertEquals($category->id, $response['data']['id']);
    }

    public function test_validations_store()
    {
        $data = [];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function test_store()
    {
        $data = [
            'name' => 'New Category'
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ]
        ]);

        $response = $this->postJson($this->endpoint, [
            'name' => 'New Cat',
            'description' => 'new desc',
            'is_active' => false,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('New Cat', $response['data']['name']);
        $this->assertEquals('new desc', $response['data']['description']);
        $this->assertEquals(false, $response['data']['is_active']);
        $this->assertDatabaseHas('categories', [
            'id' => $response['data']['id'],
            'is_active' => false,
        ]);
    }

    public function test_not_found_update()
    {
        $data = [
            'name' => 'New name',
        ];

        $response = $this->putJson("{$this->endpoint}/fake_id", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_validations_update()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$category->id}", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function test_update()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Name Updated',
        ];

        $response = $this->putJson("{$this->endpoint}/{$category->id}", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ]
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => 'Name Updated',
        ]);
    }

    public function test_not_found_delete()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$category->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    }
}
