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
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee(__('No products found'));
    }

    public function test_products_list_is_not_empty()
    {
        $user = User::factory()->create();
        $product = Product::create(['name' => 'product_test', 'price' => 10]);
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertViewHas('products', function($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_paginate_products_list_doesnt_contain_11th_record()
    {
        $user = User::factory()->create();
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertViewHas('products', function($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    public function test_products_redirects_if_not_logged_in()
    {
        Product::factory(11)->create();
        $response = $this->get('/products');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_admin_can_see_products_create_button()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/products');
        $response->assertStatus(200);
        $response->assertSee(__('Add Product'));
    }

    public function test_non_admin_cannot_see_products_create_button()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('Add Product'));
    }

    public function test_admin_can_access_products_create_page()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_product_create_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products/create');
        $response->assertStatus(403);
    }
}
