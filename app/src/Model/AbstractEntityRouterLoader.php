<?php

declare(strict_types=1);

namespace App\Model;

abstract class AbstractEntityRouterLoader
{
    public static function enableLoader(bool $enable = true): bool
    {
        return $enable;
    }
}
