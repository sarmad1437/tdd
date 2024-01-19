<?php

use App\Http\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

function error(string $message = 'Invalid request', int $code = 400): JsonResponse
{
    return ApiResponse::error($message, $code);
}

function success($data = null, string $message = 'Success'): JsonResponse|AnonymousResourceCollection
{
    return ApiResponse::success($data, $message);
}

function validationError($validator): JsonResponse
{
    return ApiResponse::validationError($validator);
}
