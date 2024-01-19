<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Validator;

class ApiResponse
{
    public static function error(string $message = 'Data is invalid', int $code = 400): JsonResponse
    {
        return self::apiResponse($message, null, null, $code);
    }

    public static function success($data, $message, $code = 200): AnonymousResourceCollection|JsonResponse
    {
        if ($data instanceof AnonymousResourceCollection) {
            return $data->additional([
                'message' => 'Success',
            ]);
        }

        return self::apiResponse($message, $data, null, $code);
    }

    public static function validationError(Validator $validator): JsonResponse
    {
        return self::apiResponse($validator->errors()->first(), null, $validator->errors(), 422);
    }

    public static function apiResponse(string $message, $data = null, $errors = null, int $code = 200): JsonResponse
    {
        $response = [];

        $response['message'] = $message;
        $response['data'] = $data;

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
