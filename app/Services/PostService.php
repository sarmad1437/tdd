<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class PostService
{
    public function all(User $user, array $data)
    {
        return Post::query()
            ->when($user, fn(Builder $query) => $query->where('user_id', $user->id))
            ->when(isset($data['paginated']) && $data['paginated'] == true,
                fn(Builder $query) => $query->paginate(),
                fn(Builder $query) => $query->get()
            );
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
