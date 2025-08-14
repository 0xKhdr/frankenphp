<?php

namespace App\Http\Controllers\Api;

use App\Actions\Units\ClearUnitsCacheAction;
use App\Actions\Units\ListUnitsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    public function __construct(
        protected ListUnitsAction $listUnitsAction,
        protected ClearUnitsCacheAction $clearCacheAction
    ) {}

    /**
     * Get units with caching (default)
     */
    public function index(): JsonResponse
    {
        $units = $this->listUnitsAction->handle(useCache: true);
        return $this->jsonResponse($units, true);
    }

    /**
     * Get fresh units directly from database (bypassing cache)
     */
    public function fresh(): JsonResponse
    {
        $units = $this->listUnitsAction->handle(useCache: false);
        return $this->jsonResponse($units, false);
    }

    /**
     * Clear the units cache
     */
    public function clearCache(): JsonResponse
    {
        $cleared = $this->clearCacheAction->handle();
        
        return response()->json([
            'success' => $cleared,
            'message' => $cleared ? 'Cache cleared successfully' : 'No cache to clear'
        ]);
    }

    /**
     * Format JSON response
     */
    protected function jsonResponse(mixed $data, bool $isCached): JsonResponse
    {
        return response()->json([
            'success' => true,
            'cached' => $isCached,
            'message' => sprintf(
                'Data retrieved %s',
                $isCached ? 'from cache' : 'fresh from database'
            ),
            'total' => count($data),
            'data' => $data
        ]);
    }
}
