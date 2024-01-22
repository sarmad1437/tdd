<?php

use App\Events\UserRegisteredEvent;
use App\Listeners\SendWelcomeMailListener;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('user can register himself', function () {
    Mail::fake();
    Event::fake();

    $this->assertDatabaseCount(User::class, 0);

    $data = [
        'name' => 'User',
        'email' => 'user@email.com',
        'password' => 12345678,
    ];

    $response = $this->postJson(route('register', $data));

    $response->assertStatus(200);

    $response = $response->json();

    //dd($response);

    expect($response['message'])->toBe('User register successfully');
    expect($response['data']['name'])->toBe($data['name']);
    expect($response['data']['email'])->toBe($data['email']);


    //$response->assertOk();

    /*$response->assertJson(fn(AssertableJson $json) => $json
        ->where('message', 'User register successfully')
        ->has('data', fn($json) => $json
            ->where('email', $data['email'])
            ->missing('password')
            ->etc()
        )
    );*/

    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'email' => $data['email'],
    ]);

    Event::assertDispatched(UserRegisteredEvent::class);

    Event::assertDispatched(UserRegisteredEvent::class, function ($e) use ($data) {
        return $e->user->email === $data["email"];
    });

    Event::assertListening(
        UserRegisteredEvent::class,
        SendWelcomeMailListener::class
    );


    Mail::to($data['email']);

    Mail::assertSent(RegisterMail::class);

    Mail::assertSent(RegisterMail::class, function ($mail) use ($data) {
        return $mail->hasTo($data['email']);
    });
});

test('user has to fill required fields', function () {
    $this->assertDatabaseCount(User::class, 0);

    $data = [];

    $response = $this->postJson(route('register', $data));

    $response->assertUnprocessable()->assertInvalid([
        'name', 'email', 'password',
    ]);
});
