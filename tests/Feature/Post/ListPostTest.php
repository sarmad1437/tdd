<?php

use App\Models\Post;
use Illuminate\Testing\Fluent\AssertableJson;

test('Guest user can\'t view all posts', function () {
    $this->getJson(route('posts.index'))->assertUnauthorized();
});

test('User can view all own posts', function () {
    $user = loginUser();

    $ownPost = Post::factory()->create(['user_id' => $user]);
    $otherPosts = Post::factory()->create();

    $response = $this->get(route('posts.index'));

    $response->assertJson(function (AssertableJson $json) use ($ownPost) {
        $json->where('message', 'Success')
            ->missingAll(['meta', 'links'])
            ->has('data.0', fn($json) => $json
                ->where('id', $ownPost->id)
                ->where('title', $ownPost->title)
                ->etc()
            );
    });

    $this->assertDatabaseCount(Post::class, 2);
});

test('User can view all paginated posts', function () {
    $user = loginUser();

    $ownPost = Post::factory()->create(['user_id' => $user]);
    $otherPosts = Post::factory()->create();

    $response = $this->get(route('posts.index', ['paginated' => true]));

    $response->assertJson(function (AssertableJson $json) use ($ownPost) {
        $json->where('message', 'Success')
            ->hasAll(['meta', 'links'])
            ->has('data',1)
            ->has('data.0', fn($json) => $json
                ->where('id', $ownPost->id)
                ->where('title', $ownPost->title)
                ->etc()
            );
    });

    $this->assertDatabaseCount(Post::class, 2);
});
