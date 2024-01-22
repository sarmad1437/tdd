<?php


use Illuminate\Testing\Fluent\AssertableJson;

test('show all published blog posts', function () {
    $post = \App\Models\Post::factory()->published()->create();
    $response = $this->getJson(route('blog.posts'));

    //$response->assertStatus(200);

    /*expect($response[0]['title'])->toBe('test 1');*/

    /*$response = $response->json('data');

    expect($response)->toHaveCount(1);

    $response->assertJsonPath('data','12');


    expect($response[0]['title'])->toBe($post->title);
    expect($response[0]['content'])->toBe($post->content);*/


    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json
        ->where('message', 'Success')
        ->has('data',1)
        ->has('data.0', fn($json) => $json
            ->where('title', $post['title'])
            ->where('content', $post['content'])
            ->etc()
        )
    );
    /*dd($response->json());*/
});
