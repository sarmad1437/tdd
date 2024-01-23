<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;

class TestHelper
{
    public static function login(User $user = null): User
    {
        $user = $user ?? User::factory()->create();

        Sanctum::actingAs($user);

        return $user;
    }

    public static function assertResponse(): void
    {
        TestResponse::macro('assertResponse', function (array $presentKeys = [], ?array $data = null, string $message = 'Success', array $missingKeys = [], array $hasKeys = [], ?int $length = null, bool $isPaginated = false) {
            $this->assertOk();

            $this->assertJson(function (AssertableJson $json) use ($isPaginated, $hasKeys, $missingKeys, $presentKeys, $length, $message, $data) {
                $json->where('message', $message);

                if ($data) {
                    $data = Arr::dot($data);

                    foreach ($hasKeys as $hasKey) {
                        $json->has('data.'.$hasKey);
                    }

                    if (! empty($hasKeys)) {
                        $length = count($hasKeys);
                    }

                    if ($length != null) {
                        $json->has('data', $length);
                    }

                    if ($isPaginated) {
                        $json->hasAll(['meta', 'links']);
                    } else {
                        $json->missingAll(['meta', 'links']);
                    }

                    $json->has('data', function ($json) use ($missingKeys, $presentKeys, $data) {
                        foreach ($presentKeys as $presentKey) {
                            $json->where($presentKey, $data[$presentKey]);
                        }
                        foreach ($missingKeys as $missingKey) {
                            $json->missing($missingKey);
                        }
                        $json->etc();
                    });
                } else {
                    $json->where('data', $data);
                }
            });
        });
    }
}
