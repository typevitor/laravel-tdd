<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ProductsTest extends TestCase
{
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
}
