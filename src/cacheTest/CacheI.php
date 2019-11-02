<?php

namespace Multicache\cacheTest;

interface CacheI
{
    public function connect();
    public function set();
    public function get();
}
