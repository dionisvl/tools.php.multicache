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
