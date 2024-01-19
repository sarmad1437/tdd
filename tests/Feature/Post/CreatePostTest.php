<?php

use App\Models\Post;
use Illuminate\Testing\Fluent\AssertableJson;

test('Guest user can\'t create post', function () {
    $this->postJson(route('posts.store'))->assertUnauthorized();
});

test('User has to fill required fields for creating.', function () {
    loginUser();

    $response = $this->postJson(route('posts.store'));

    $response->assertUnprocessable()->assertInvalid(['name', 'slug', 'description']);
});

test('User can create new post', function () {
    $this->assertDatabaseCount(Post::class, 0);

    $user = loginUser();

    $data = [
        'name' => 'post 1',
        'slug' => 'post-1',
        'description' => 'description',
    ];

    $response = $this->postJson(route('posts.store', $data));

    $response->assertOk();

    $response->assertJson(function (AssertableJson $json) use ($user, $data) {
        $json->where('message', 'Post added successfully.')
            ->has('data', fn ($json) => $json
                ->where('name', $data['name'])
                ->where('slug', $data['slug'])
                ->where('description', $data['description'])
                ->where('user_id', $user->id)
                ->etc()
            );
    });

    $this->assertDatabaseCount(Post::class, 1);
});
