<?php

test('public registration page is unavailable', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});

test('public registration submission is unavailable', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertGuest();
    $response->assertNotFound();
});
