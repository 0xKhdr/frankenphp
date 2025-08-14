<?php

namespace App\Actions\Units;

use App\Actions\Action;
use Illuminate\Support\Facades\Cache;

class ClearUnitsCacheAction extends Action
{
    protected string $cacheKey = 'api:units:list';

    public function handle(): bool
    {
        return Cache::forget($this->cacheKey);
    }
}
