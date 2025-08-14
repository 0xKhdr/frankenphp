<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UnitController extends Controller
{
    protected int $cacheTtl = 3600; // 1 hour

    protected string $cacheKey = 'api:units:list';

    /**
     * Get units with caching (default)
     */
    public function index(): JsonResponse
    {
        $units = Cache::remember($this->cacheKey, $this->cacheTtl, function () {
            return $this->getUnits();
        });

        return $this->jsonResponse($units, true);
    }

    /**
     * Get fresh units directly from database (bypassing cache)
     */
    public function fresh(): JsonResponse
    {
        $units = $this->getUnits();

        return $this->jsonResponse($units, false);
    }

    /**
     * Clear the units cache
     */
    public function clearCache(): JsonResponse
    {
        $cleared = Cache::forget($this->cacheKey);

        return response()->json([
            'success' => $cleared,
            'message' => $cleared ? 'Cache cleared successfully' : 'No cache to clear',
        ]);
    }

    /**
     * Get fresh units from database
     */
    protected function getUnits(): Collection
    {
        return Unit::all();
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
            'data' => $data,
        ]);
    }
}
