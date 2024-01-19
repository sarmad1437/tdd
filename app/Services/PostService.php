<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class PostService
{
    public function all(array $data)
    {
        return Post::query()->get();
    }

    public function create(User $user, array $postData)
    {
        return $user->posts()->create($postData);
    }

    public function update(Post $post, array $postData): bool
    {
        return $post->update($postData);
    }

    public function delete(Post $post): ?bool
    {
        return $post->delete();
    }
}
