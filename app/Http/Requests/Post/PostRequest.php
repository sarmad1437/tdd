<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:60'],
            'slug' => ['required', Rule::unique(Post::class, 'slug')->ignore($this->post)],
            'status' => ['boolean'],
            'content' => ['required'],
        ];
    }
}
