<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    
    protected function success($data , string $message = null): JsonResponse
    {
        return $this->createJsonResponse(true, $data , 200 , $message);
    }

    protected function created($data, string $message = null): JsonResponse
    {
        return $this->createJsonResponse(true, $data, 201, $message);
    }
    protected function error($data, string $message = null): JsonResponse
    {
        return $this->createJsonResponse(false, $data, 400, $message);
    }
    protected function notFound($data, string $message = null): JsonResponse
    {
        return $this->createJsonResponse(false, $data, 404, $message);
    }
    protected function unauthorized($data, string $message = null): JsonResponse
    {
        return $this->createJsonResponse(false, $data, 401, $message);
    }
    protected function unprocessable($data, string $message = null): JsonResponse
    {
        return $this->createJsonResponse(false, $data, 422, $message);
    }
    private function createJsonResponse(bool $status, $data, int $code, string $message = null): JsonResponse
    {
        $message = $message ?? ($status ? 'Operation completed successfully.' : 'Operation failed.');

        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
            'code' => $code
        ], $code);
    }
}

