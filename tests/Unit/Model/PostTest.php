<?php

use App\Models\Post;

test('return published posts only', function () {
    $publishedPost = Post::factory()->published()->create();
    Post::factory()->unPublished()->create();

    $posts = Post::published()->get();

    expect($posts)->toHaveCount(1);
    expect($posts[0]['id'])->toBe($publishedPost->id);
});
