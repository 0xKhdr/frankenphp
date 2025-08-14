<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;

abstract class Action
{
    abstract public function handle();

    public function execute()
    {
        return $this->handle();
    }

    protected function success($data, ?string $message = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function error(string $message, int $status = 400, ?array $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
