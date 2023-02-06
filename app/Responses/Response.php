<?php

namespace App\Responses;

use Illuminate\Http\JsonResponse;

class Response
{
    public static function succes($message): JsonResponse
    {
        return static::base(true, $message, null);
    }

    public static function error($message): JsonResponse
    {
        return static::base(false, $message, null);
    }

    public static function data($message, $data, $status): JsonResponse
    {
        return static::base($status, $message, $data);
    }

    private static function base($status, $message, $data = null): JsonResponse
    {
        $json = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $json['data'] = $data;
        }

        return response()->json($json);
    }

}
