<?php

namespace App\Actions\Units;

use App\Actions\AbstractAction;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ListUnitsAction extends AbstractAction
{
    protected string $cacheKey = 'units:all';

    protected int $cacheTtl = 3600; // TTL in seconds

    /**
     * Execute the action to get all units with caching
     */
    public function handle(bool $fresh = false): Collection
    {
        if ($fresh) {
            Cache::forget($this->cacheKey);
        }

        return Cache::remember($this->cacheKey, $this->cacheTtl, function () {
            return Unit::all();
        });
    }

    /**
     * Clear the units cache
     */
    public function clearCache(): bool
    {
        return Cache::forget($this->cacheKey);
    }

    /**
     * Get fresh units directly from database (bypassing cache)
     *
     * @throws Exception
     */
    public function fresh(): Collection
    {
        return $this->execute(fresh: true);
    }
}
