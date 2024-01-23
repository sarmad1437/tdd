<?php


use App\Models\Post;
use Illuminate\Testing\Fluent\AssertableJson;

test('show all published blog posts', function () {
    $response = $this->getJson(route('blog.posts'));

    $response->assertStatus(200);

    $response = $response->json('data');

    expect($response)->toHaveCount(1);

    expect($response[0]['title'])->toBe('test 1');


    /*$post = \App\Models\Post::factory()->create(['status' => true]);
    \App\Models\Post::factory()->create(['status' => false]);

    $response = $this->getJson(route('blog.posts'));

    $response = $response->json('data');

    expect($response[0]['title'])->toBe($post->title);
    expect($response[0]['content'])->toBe($post->content);*/


    /*$post = Post::factory()->published()->create();
    Post::factory()->unPublished()->create();

    $response = $this->getJson(route('blog.posts'));

    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json
        ->where('message', 'Success')
        ->has('data',1)
        ->has('data.0', fn($json) => $json
            ->where('title', $post['title'])
            ->where('content', $post['content'])
            ->etc()
        )
    );*/

});
