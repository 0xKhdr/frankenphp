<?php

namespace App\Actions;

use Exception;

abstract class AbstractAction
{
    /**
     * @throws Exception
     */
    public static function execute(...$args): mixed
    {
        if (! method_exists(static::class, 'handle')) {
            throw new Exception('Method handle() is not implemented in '.static::class);
        }

        return app(static::class)->handle(...$args);
    }
}
