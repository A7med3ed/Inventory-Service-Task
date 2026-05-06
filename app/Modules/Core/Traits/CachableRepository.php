<?php

namespace App\Modules\Core\Traits;

use Illuminate\Support\Facades\Cache;

trait CachableRepository
{
    /**
     * Cache key prefix for the repository
     */
    protected string $cachePrefix = 'repository';

    /**
     * Cache duration in minutes
     */
    protected int $cacheDuration = 60;

    /**
     * Get cache key
     */
    protected function getCacheKey(string $key): string
    {
        return "{$this->cachePrefix}:{$key}";
    }

    /**
     * Get value from cache or execute callback
     */
    protected function remember(string $key, callable $callback)
    {
        return Cache::remember(
            $this->getCacheKey($key),
            now()->addMinutes($this->cacheDuration),
            $callback
        );
    }

    /**
     * Get value from cache
     */
    protected function get(string $key)
    {
        return Cache::get($this->getCacheKey($key));
    }

    /**
     * Put value in cache
     */
    protected function put(string $key, $value, int $minutes = null): void
    {
        Cache::put(
            $this->getCacheKey($key),
            $value,
            now()->addMinutes($minutes ?? $this->cacheDuration)
        );
    }

    /**
     * Forget cache key
     */
    protected function forget(string $key): void
    {
        Cache::forget($this->getCacheKey($key));
    }

    /**
     * Forget multiple cache keys by pattern
     */
    protected function forgetPattern(string $pattern): void
    {
        Cache::tags([$pattern])->flush();
    }

    /**
     * Clear all cache for this repository
     */
    protected function clearCache(): void
    {
        Cache::tags([$this->cachePrefix])->flush();
    }
}
