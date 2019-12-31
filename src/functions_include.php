<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists("dump")) {
    function dump($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}

if (!function_exists("getParam")) {
    function getParam($param, $file)
    {
        $fp = fopen($file, 'r');
        while (($line = fgets($fp)) !== false) {
            if (preg_match("~.*\b$param(.*)~", $line, $matches)) {
                return $matches[1];
            }
        }
        return false;
    }
}
