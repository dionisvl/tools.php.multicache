<?php

namespace Multicache\cacheTest;

Class CacheTest
{
    protected $test_data;
    protected $id = 4;
    function test_data(){    // тестовые данные для тестового кеширования
        return implode(PHP_EOL,array_fill(0, 10000, microtime()));
    }
}
