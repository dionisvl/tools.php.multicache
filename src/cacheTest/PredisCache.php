<?php

namespace Multicache\cacheTest;

use Predis;

class PredisCache extends CacheTest implements CacheI
{
    private $scheme = 'tcp';
    private $db_host = '127.0.0.1';
    private $db_port = 6379;

    public function __construct($scheme, $db_host, $db_port)
    {
        $this->scheme = $scheme;
        $this->db_host = $db_host;
        $this->db_port = $db_port;
        $this->test_data = $this->test_data();
    }

    public function connect()
    {
        $this->connect = new Predis\Client([
            'scheme' => $this->scheme,
            'host' => $this->db_host,
            'port' => $this->db_port,
        ]);
    }

    public function set($key = 'my_cache', $val = '')
    {
        $key = $this->id;
        $val = $this->test_data;
        $this->connect->set($key, $val);
    }

    public function get($key = 'my_cache')
    {
        $key = $this->id;
        $data = $this->connect->get($key);
        return isset($data) ? $data : false;
    }
}
