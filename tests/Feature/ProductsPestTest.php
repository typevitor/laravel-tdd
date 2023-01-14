<?php

beforeEach(function() {
    $this->user = createUser(isAdmin: false);
});

test('products list is empyy', function () {
    $this->actingAs($this->user)->get('/products')
        ->assertStatus(200)
        ->assertSee(__('No products found'));
});
