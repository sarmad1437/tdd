<?php

use App\Models\Post;
use Illuminate\Testing\Fluent\AssertableJson;

test('Guest user can\'t view all posts', function () {
    $this->getJson(route('posts.index'))->assertUnauthorized();
});

test('User can view all own posts', function () {
    $user = loginUser();

    $ownPost = Post::factory(1)->create(['user_id' => $user]);
    $otherPosts = Post::factory(1)->create();

    $response = $this->get(route('posts.index'));

    $response->assertJson(function (AssertableJson $json) use ($ownPost) {
        $json->where('message', 'Success')
            ->missingAll(['meta', 'links'])
            ->has('data.0', fn ($json) => $json
                ->where('id', $ownPost->id)
                ->where('name', $ownPost->name)
                ->etc()
            );
        $json->all(['id', 'name'], $ownPost);
    });

    $this->assertDatabaseCount(Post::class, 2);
});

test('User can view all paginated posts', function () {
    $user = loginUser();

    $ownPost = Post::factory(1)->create(['user_id' => $user]);
    $otherPosts = Post::factory(1)->create();

    $response = $this->get(route('posts.index', ['paginated' => true]));

    $response->assertJson(function (AssertableJson $json) use ($currency) {
        $json->where('message', 'Success')
            ->hasAll(['meta', 'links'])
            ->has('data.0', fn ($json) => $json
                ->where('name', $currency->name)
                ->etc()
            );
        $json->paginated(['name'], $currency);
    });

    $this->assertDatabaseCount(Post::class, 2);
});

test('User can filter by status paginated currencies', function () {
    loginUser();

    $activeCurrency = Currency::factory()->create(['status' => true]);
    $inactiveCurrency = Currency::factory()->create(['status' => false]);

    $response = $this->get(route('currencies.index', ['paginated' => true, 'status' => true]));

    $response->assertOk();

    $this->assertDatabaseCount(Currency::class, 2);

    $response->assertJson(function (AssertableJson $json) use ($activeCurrency) {
        $json->where('message', 'Success')
            ->hasAll(['meta', 'links'])
            ->has('data', 1)
            ->has('data.0', fn ($json) => $json
                ->where('name', $activeCurrency->name)
                ->etc()
            );
        $json->all(['name'], $activeCurrency);
    });
});
