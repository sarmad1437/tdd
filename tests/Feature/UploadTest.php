<?php


use Illuminate\Http\UploadedFile;

test('user can upload file', function () {
    Storage::fake();

    $file = UploadedFile::fake()->image('test.png');

    $data = [
        'file' => $file
    ];

    $response = $this->post(route('upload'),$data);

    $response->assertOk();

    $path = $file->hashName();

    $response->assertJsonPath('data',$path);

    Storage::assertExists($path);
});
