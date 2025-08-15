<?php

namespace App\Actions;

use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

abstract class AbstractAction
{
    /**
     * @throws Exception
     */
    public static function execute(...$args): mixed
    {
        if (!method_exists(static::class, 'handle')) {
            throw new Exception('Method handle() is not implemented in ' . static::class);
        }

        return app(static::class)->handle(...$args);
    }
}
