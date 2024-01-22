<?php


test('show all published blog posts', function () {
    $post = \App\Models\Post::factory()->published()->create();
    $response = $this->getJson(route('blog.posts'));

    $response->assertStatus(200);

    $response = $response->json('data');

    expect($response)->toHaveCount(1);

    /*expect($response[0]['title'])->toBe('test 1');*/
    expect($response[0]['title'])->toBe($post->title);
});
