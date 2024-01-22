<?php


test('user can upload file', function () {

    Storage::fake('public');

    $file = \Illuminate\Http\UploadedFile::fake()->image('test.png');

    $data = [
        'file' => $file
    ];

    $response = $this->post(route('upload'),$data);

    $response->assertOk();

    $response->assertJsonPath('data','uploads/test.png');

     expect($response[0]['title'])->toBe($post->title);

    Storage::assertExists($response->json('data'));
});
