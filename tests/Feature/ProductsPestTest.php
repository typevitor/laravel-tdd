<?php

beforeEach(function() {
    $this->user = \App\Models\User::factory()->create();
});

test('products list is empyy', function () {
    $this->actingAs($this->user)->get('/products')
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});
