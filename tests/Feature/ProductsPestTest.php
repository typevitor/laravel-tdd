<?php

beforeEach(function() {
    $this->user = createUser(isAdmin: false);
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
        ->assertViewHas('products', function($collection) use ($product) {
            return $collection->contains($product);
        });
});
