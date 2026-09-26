<?php

test('registration route redirects to login', function () {
    $response = $this->get('/register');

    $response->assertRedirect('/');
});

test('registration post is disabled and redirects to login', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect('/');
});
