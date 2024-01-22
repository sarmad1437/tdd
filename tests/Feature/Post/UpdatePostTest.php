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

    $response->assertUnprocessable()->assertInvalid(['name','slug','description']);
});

test('User can update Post', function () {
    $user = loginUser();

    $post = Post::factory()->for($user)->create();

    $data = [
        'name' => 'test update',
        'slug' => $post->slug,
        'description' => $post->description,
    ];

    $response = $this->putJson(route('posts.update', ['post' => $post->id]), $data);

    $response->assertJson(function (AssertableJson $json) use ($user, $data) {
        $json->where('message', 'Post updated successfully.')
            ->has('data', fn($json) => $json
                ->where('name', $data['name'])
                ->where('slug', $data['slug'])
                ->where('description', $data['description'])
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
