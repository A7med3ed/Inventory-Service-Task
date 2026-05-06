<?php

namespace App\Modules\Core\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Success response
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $statusCode);
    }

    /**
     * Paginated response
     */
    public static function paginated($data, string $message = 'Success', array $meta = []): JsonResponse
    {
        $pagination = [];
        if ($data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $pagination = [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'meta' => array_merge($meta, ['pagination' => $pagination]),
        ], 200);
    }

    /**
     * Error response
     */
    public static function error(string $message = 'Error', int $statusCode = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => null,
        ], $statusCode);
    }

    /**
     * Not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Validation error response
     */
    public static function validationError(array $errors): JsonResponse
    {
        return self::error('Validation failed', 422, $errors);
    }

    /**
     * Unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Server error response
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return self::error($message, 500);
    }
}
