<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->authorizeResource(Post::class);
    }

    public function index()
    {
        $data = request()->all() + ['userId' => auth()->id()];

        $posts = $this->postService->all($data);

        return success(PostResource::collection($posts));
    }

    public function store(PostRequest $postRequest)
    {
        $post = $this->postService->create(auth()->user(), $postRequest->validated());

        return success(new PostResource($post), 'Post added successfully.');
    }

    public function update(PostRequest $postRequest, Post $post)
    {
        $this->postService->update($post, $postRequest->validated());

        return success(new PostResource($post), 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return success();
    }
}
