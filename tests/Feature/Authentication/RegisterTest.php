<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('user can register himself', function () {
    $this->assertDatabaseCount(User::class, 0);

    $data = [
        'name' => 'User',
        'email' => 'user@email.com',
        'password' => 12345678,
    ];

    $response = $this->postJson(route('register', $data));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->where('message', 'User register successfully')
        ->has('data', fn($json) => $json
            ->where('email', $data['email'])
            ->missing('password')
            ->etc()
        )
    );

    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'email' => $data['email'],
    ]);
});

test('user has to fill required fields', function () {
    $this->assertDatabaseCount(User::class, 0);

    $data = [];

    $response = $this->postJson(route('register', $data));

    $response->assertUnprocessable()->assertInvalid([
        'name', 'email', 'password',
    ]);
});
