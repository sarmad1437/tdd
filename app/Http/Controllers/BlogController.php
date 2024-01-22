<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()->get();

        /*$posts = collect();*/

        return success(PostResource::collection($posts));
    }
}
