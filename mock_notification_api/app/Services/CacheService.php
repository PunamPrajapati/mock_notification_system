<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class CacheService
{
    /**
     * Get value from Redis cache or run the callback to store it.
     *
     * @param string $key Cache key
     * @param int $seconds Expiration time in seconds
     * @param callable $callback Callback to get fresh data if cache miss
     * @return mixed
     */
    public function remember(string $key, int $seconds, callable $callback)
    {
        $value = Redis::get($key);

        if ($value !== null) {
            // Return decoded value if JSON, else raw string
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : $value;
        }

        // Cache miss: fetch data, cache it and return
        $value = $callback();

        $storedValue = is_string($value) ? $value : json_encode($value);

        Redis::setex($key, $seconds, $storedValue);

        return $value;
    }

    /**
     * Remove a cache key from Redis
     *
     * @param string $key
     * @return int Number of keys removed
     */
    public function forget(array $keys)
    {
        return Redis::del($keys);
    }
}
