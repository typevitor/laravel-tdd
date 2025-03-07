<?php

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->property = Property::factory(['max_occupants' => 2])->create();
});

test('should create a new booking', function () {
    $booking = [
        'property_id' => $this->property->id,
        'user_id' => $this->user->id,
        'start_date' => '2025-01-10',
        'end_date' => '2025-01-15',
        'occupants' => 2
    ];
    $this->post('/api/v1/bookings', $booking)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'property_id',
                'user_id',
                'occupants',
            ],
            'message'
        ])
        ->assertJsonFragment(['message' => 'Booking created successfully']);
    $this->assertDatabaseHas('bookings', $booking);

});

test('should return error with an invalid date', function () {
    $booking = [
        'property_id' => $this->property->id,
        'user_id' => $this->user->id,
        'start_date' => 'invalid-date',
        'end_date' => '2025-01-15',
        'occupants' => 2
    ];
    $this->post('/api/v1/bookings', $booking)
        ->assertStatus(400)
        ->assertJsonStructure(['message']);
});

test('should return error with an start date higher than end date', function () {
    $booking = [
        'property_id' => $this->property->id,
        'user_id' => $this->user->id,
        'start_date' => '2025-01-20',
        'end_date' => '2025-01-15',
        'occupants' => 2
    ];
    $this->post('/api/v1/bookings', $booking)
        ->assertStatus(422)
        ->assertJsonStructure(['message']);
});


test('should return error with an invalid user', function () {
    $booking = [
        'property_id' => $this->property->id,
        'user_id' => 200,
        'start_date' => '2025-01-20',
        'end_date' => '2025-01-25',
        'occupants' => 2
    ];
    $this->post('/api/v1/bookings', $booking)
        ->assertStatus(400)
        ->assertJsonStructure(['message']);
});

test('should return error with an invalid property', function () {
    $booking = [
        'property_id' => 200,
        'user_id' => $this->user->id,
        'start_date' => '2025-01-20',
        'end_date' => '2025-01-25',
        'occupants' => 2
    ];
    $this->post('/api/v1/bookings', $booking)
        ->assertStatus(400)
        ->assertJsonStructure(['message']);
});
