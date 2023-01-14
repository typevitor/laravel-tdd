<?php

test('unauthenticaded user cannot access products')
    ->get('/products')
    ->assertRedirect('login');
