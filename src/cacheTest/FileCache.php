<?php

namespace Multicache\cacheTest;

class FileCache extends CacheTest implements CacheI
{
    public function __construct($persistent_connect = false)
    {
        $this->test_data = $this->test_data();
    }

    public function connect()
    {
        usleep(1);
        $x = file_exists($_SERVER['DOCUMENT_ROOT'] . "/cache/xxx");
    }

    public function set($key = 'my_cache', $val = '')
    {
        $key = $this->id;
        $val = $this->test_data;
        $val = var_export($val, true);
        // HHVM fails at __set_state, so just use object cast for now
        $val = str_replace('stdClass::__set_state', '(object)', $val);
        // Write to temp file first to ensure atomicity
        $tmp = $_SERVER['DOCUMENT_ROOT'] . "/cache/$key." . uniqid('', true) . '.tmp';
        file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
        rename($tmp, $_SERVER['DOCUMENT_ROOT'] . "/cache/$key");
    }

    public function get($key = 'my_cache')
    {
        $key = $this->id;
        include $_SERVER['DOCUMENT_ROOT'] . "/cache/$key";
        return isset($val) ? $val : false;
    }
}
