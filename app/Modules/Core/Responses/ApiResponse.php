<?php

namespace App\Modules\Core\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        array $meta = [],
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'meta'    => empty($meta) ? null : $meta,
        ], $status);
    }

    public static function created(mixed $data = null): JsonResponse
    {
        return static::success($data, [], 201);
    }

    public static function error(
        string $message,
        int $status = 400,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => empty($errors) ? null : $errors,
        ], $status);
    }

    public static function paginated(
        mixed $data,
        \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                ],
            ],
        ]);
    }
}
