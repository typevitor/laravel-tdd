<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_list_is_empty()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
        ->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee(__('No products found'));
    }

    public function test_products_list_is_not_empty()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
        ->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $product = Product::create(['name' => 'product_test', 'price' => 10]);

        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertViewHas('products', function($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_paginate_products_list_doesnt_contain_11th_record()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
        ->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $products = Product::factory(11)->create();
        $lastProduct = $products->last();

        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertViewHas('products', function($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }
}
