<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    public function test_api_products_list_is_empty()
    {
        $product = Product::factory()->create();
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJson([$product->toArray()]);
    }

    public function test_api_create_product_successful()
    {
        $product = [
            'name' => 'Product API',
            'price' => 123,
        ];
        $response = $this->postJson('/api/products', $product);
        $response->assertStatus(201);
        $response->assertJson($product);
    }

    public function test_api_invalid_products_returns_errors()
    {
        $product = [
            'name' => '',
            'price' => 123,
        ];
        $response = $this->postJson('/api/products', $product);
        $response->assertStatus(422);
    }
}
