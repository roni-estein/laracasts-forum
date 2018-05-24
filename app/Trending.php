<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
         return $trending = array_map('json_decode',Redis::zrevrange(self::cacheKey(), 0, 4));

    }

    public function push($thread)
    {
        Redis::zincrby(self::cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));
    }

    public static function cacheKey()
    {
        return app()->environment() === 'testing' ? 'testing_trending_threads' : 'trending_threads';
    }

    public static function reset()
    {
        Redis::del(self::cacheKey());
    }
}
