<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
    }

    public function test_products_list_is_empty()
    {
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee(__('No products found'));
    }

    public function test_products_list_is_not_empty()
    {
        $product = Product::create(['name' => 'product_test', 'price' => 10]);
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_paginate_products_list_doesnt_contain_11th_record()
    {
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
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
        $response = $this->actingAs($this->admin)->get('/products');
        $response->assertStatus(200);
        $response->assertSee(__('Add Product'));
    }

    public function test_non_admin_cannot_see_products_create_button()
    {
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('Add Product'));
    }

    public function test_admin_can_access_products_create_page()
    {
        $response = $this->actingAs($this->admin)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_product_create_page()
    {
        $response = $this->actingAs($this->user)->get('/products/create');
        $response->assertStatus(403);
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create(['is_admin' => $isAdmin]);
    }

    public function test_create_product_successful()
    {
        $product = [
            'name' => 'test',
            'price' => 1234
        ];
        $response = $this->actingAs($this->admin)->post('/products', $product);
        $response->assertStatus(302);
        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    public function test_edit_product_form_contain_correct_values()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->get('/products/'.$product->id.'/edit');
        $response->assertStatus(200);
        $response->assertSee('value="'.$product->name.'"', escape: false);
        $response->assertSee('value="'.$product->price.'.00"', escape: false);
        $response->assertViewHas('product', $product);
    }

    public function test_product_update_validation_error_redirects_back_to_form()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->put('/products/'.$product->id, [
            'name' => '',
            'price' => 123
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
        $response->assertInvalid(['name']);
    }

    public function test_product_delete_product_successful()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->delete('/products/'.$product->id);
        $response->assertStatus(302);
        $response->assertRedirect('/products');
        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);
    }
}
