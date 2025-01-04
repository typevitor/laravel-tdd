<?php

beforeEach(function () {
    $this->user = createUser(isAdmin: false);
    $this->admin = createUser(isAdmin: true);
});

test('products list is empyy', function () {
    $this->actingAs($this->user)->get('/products')
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});

test('products list is not empyy', function () {
    $product = \App\Models\Product::create(['name' => 'product_test', 'price' => 10]);
    $this->actingAs($this->user)->get('/products')
        ->assertStatus(200)
        ->assertDontSee(__('No products found'))
        ->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
});

test('create product successful', function () {
    $product = [
        'name' => 'test',
        'price' => '1234.00',
    ];
    $this->actingAs($this->admin)->post('/products', $product)
        ->assertStatus(302)
        ->assertRedirect('/products');

    $this->assertDatabaseHas('products', $product);

    $lastProduct = \App\Models\Product::latest()->first();
    //$this->assertEquals($product['name'], $lastProduct->name);
    //$this->assertEquals($product['price'], $lastProduct->price);
    expect($lastProduct->name)->toBe($product['name']);
    expect($lastProduct->price)->toBe($product['price']);
});
