<?php

use App\Models\Post;
use Illuminate\Testing\Fluent\AssertableJson;

test('Guest user can\'t create post', function () {
    $this->postJson(route('posts.store'))->assertUnauthorized();
});

test('User has to fill required fields for creating.', function () {
    loginUser();

    $response = $this->postJson(route('posts.store'));

    $response->assertUnprocessable()->assertInvalid(['title', 'slug', 'content']);
});

test('User can create new post', function () {
    $this->assertDatabaseCount(Post::class, 0);

    $user = loginUser();

    $data = [
        'title' => 'post 1',
        'slug' => 'post-1',
        'content' => 'description',
    ];

    $response = $this->postJson(route('posts.store', $data));

    $response->assertOk();

    $response->assertJson(function (AssertableJson $json) use ($user, $data) {
        $json->where('message', 'Post added successfully.')
            ->has('data', fn ($json) => $json
                ->where('title', $data['title'])
                ->where('slug', $data['slug'])
                ->where('content', $data['content'])
                ->where('user_id', $user->id)
                ->etc()
            );
    });

    $this->assertDatabaseCount(Post::class, 1);

    $post = Post::first();

    expect($post->title)->toBe($data['title']);
    expect($post->slug)->toBe($data['slug']);
    expect($post->content)->toBe($data['content']);
    expect($post->user_id)->toBe($user->id);

    $this->assertDatabaseHas(Post::class, [
        'title' => $data['title'],
        'slug' => $data['slug'],
        'content' => $data['content'],
        'user_id' => $user->id,
    ]);
});
