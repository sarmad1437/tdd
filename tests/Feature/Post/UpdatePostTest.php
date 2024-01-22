<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('Guest user can\'t update post', function () {
    $post = Post::factory()->create();

    $this->putJson(route('posts.update', ['post' => $post->id]))->assertUnauthorized();
});

test('User has to fill required fields for update.', function () {
    $user = loginUser();

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->putJson(route('posts.update', ['post' => $post->id]));

    $response->assertUnprocessable()->assertInvalid(['title','slug','content']);
});

test('User can update Post', function () {
    $user = loginUser();

    $post = Post::factory()->for($user)->create();

    $data = [
        'title' => 'test update',
        'slug' => $post->slug,
        'content' => $post->content,
    ];

    $response = $this->putJson(route('posts.update', ['post' => $post->id]), $data);

    $response->assertJson(function (AssertableJson $json) use ($user, $data) {
        $json->where('message', 'Post updated successfully.')
            ->has('data', fn($json) => $json
                ->where('title', $data['title'])
                ->where('slug', $data['slug'])
                ->where('content', $data['content'])
                ->where('user_id', $user->id)
                ->etc()
            );
    });

    $this->assertDatabaseCount(Post::class, 1);
});

test('User can\'t update unauthorized post', function () {
    $otherUser = User::factory()->create();

    loginUser();

    $otherPost = Post::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->putJson(route('posts.update', ['post' => $otherPost->id]));

    $response->assertForbidden();
});
