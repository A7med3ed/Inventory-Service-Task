<?php

namespace App\Modules\Core\Traits;

use Illuminate\Support\Facades\Cache;

trait CachableRepository
{
    protected int $cacheTtl = 300;

    protected function getCacheTag(): string
    {
        return static::class;
    }

    protected function remember(string $key, callable $callback): mixed
    {
        return Cache::tags([$this->getCacheTag()])
            ->remember($key, $this->cacheTtl, $callback);
    }

    protected function flushCache(): void
    {
        Cache::tags([$this->getCacheTag()])->flush();
    }
}
