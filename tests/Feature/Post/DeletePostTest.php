<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('Guest user can\'t delete post', function () {
    $this->deleteJson(route('posts.destroy', ['post' => 1]))->assertUnauthorized();
});

test('User can delete own post', function () {
    $user = loginUser();

    Post::factory()->create(['user_id' => $user->id]);

    $this->assertDatabaseCount(Post::class, 1);

    $response = $this->deleteJson(route('posts.destroy', ['post' => 1]));

    $response->assertJson(function (AssertableJson $json) {
        $json->where('message', 'Success')
            ->where('data', null);
    });

    $this->assertDatabaseCount(Post::class, 0);
});

test('User can\'t delete unauthorized post', function () {
    $otherUser = User::factory()->create();

    loginUser();

    $otherPost = Post::factory()->create(['user_id' => $otherUser->id]);

    $this->assertDatabaseCount(Post::class, 1);

    $response = $this->deleteJson(route('posts.destroy', ['post' => $otherPost->id]));

    $response->assertForbidden();

    $this->assertDatabaseCount(Post::class, 1);
});
