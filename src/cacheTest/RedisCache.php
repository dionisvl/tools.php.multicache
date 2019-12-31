<?php

namespace Multicache\cacheTest;

class RedisCache extends CacheTest implements CacheI
{
    private $db_host = '127.0.0.1';
    private $db_port = 6379;
    private $persistent_connect = false;
    private $connect;

    private $key;

    public function __construct($db_host, $db_port, $persistent_connect = false)
    {
        $this->db_host = $db_host;
        $this->db_port = $db_port;
        $this->test_data = $this->test_data();
        $this->persistent_connect = $persistent_connect;
    }

    public function connect()
    {
        $redis = new \Redis();
        if ($this->persistent_connect) {
            $redis->pconnect($this->db_host, $this->db_port);
        } else {
            $redis->connect($this->db_host, $this->db_port);
        }
        $this->connect = $redis;
    }

    public function set($key = 'my_cache', $val = '')
    {
        //$key = $this->id;
        $this->key = microtime();
        $val = $this->test_data;
        $this->connect->set($this->key, $val);
    }

    public function get($key = 'my_cache')
    {
        $key = $this->key;
        $data = $this->connect->get($key);
        return isset($data) ? $data : false;
    }
}
