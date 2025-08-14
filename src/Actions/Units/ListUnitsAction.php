<?php

namespace App\Actions\Units;

use App\Actions\Action;
use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class ListUnitsAction extends Action
{
    protected int $cacheTtl = 3600; // 1 hour
    protected string $cacheKey = 'api:units:list';

    public function handle(bool $useCache = true)
    {
        if ($useCache && Cache::has($this->cacheKey)) {
            return Cache::get($this->cacheKey);
        }

        $units = Unit::all();
        
        if ($useCache) {
            Cache::put($this->cacheKey, $units, $this->cacheTtl);
        }

        return $units;
    }
}
