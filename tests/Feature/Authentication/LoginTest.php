<?php

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

test('admin can login with correct credentials', function () {
    $data = [
        'email' => 'admin@email.com',
        'password' => '12345678',
    ];

    $this->seed(AdminSeeder::class);

    $response = $this->postJson(route('login'), $data);

    expect($response)->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->where('message', 'User login successfully')
        ->has('data.user', fn($json) => $json
            ->where('email', $data['email'])
            ->missing('password')
            ->etc()
        )
        ->has('data.token'));
});

test('user can login using correct credentials', function () {
    $user = User::factory()->create([
        'password' => 12345678,
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 12345678,
    ]);

    expect($response)->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->where('message', 'User login successfully')
        ->has('data.user', fn ($json) => $json
            ->where('email', $user->email)
            ->where('is_admin',false)
            ->missing('password')
            ->etc()
        )
        ->has('data.token')
    );
});

test('user has to fill required fields', function () {
    $response = $this->postJson(route('login'));

    expect($response)->assertUnprocessable()
        ->assertInvalid(['email', 'password']);
});

test('user can\'t login using incorrect credentials', function () {
    $user = User::factory()->create([
        'password' => 12345678,
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'wrong',
    ]);

    $response->assertBadRequest();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->where('message', 'The provided credentials do not match our records.')
        ->where('data', null)
    );
});
