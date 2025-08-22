<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function testcreateAndGetProduct()
    {
        $response = $this->postJson('/api/product', [
            'name' => 'Test Product',
            'price' => 99.9,
        ]);

        $response->assertStatus(201);
        $id = $response->json('id');

        $getResponse = $this->getJson("/api/product/{$id}");
        $getResponse->assertStatus(200)
        ->assertJson([
            'name' => 'Test Product',
            'price' => 99.9,
        ]);
    }
}
