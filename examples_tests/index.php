<?php
require  '../vendor/autoload.php';

use Multicache\cacheTest\MysqliCache;
use Multicache\cacheTest\PdoMysqlCache;
use Multicache\cacheTest\FileCache;
use Multicache\cacheTest\PredisCache;
use Multicache\cacheTest\RedisCache;

$db_user = 'root';
$db_pass = '';
$db_host = '127.0.0.1';
$db_name = 'test';
$tableName = 'test';
$persistent = 0;
$dsn = "mysql:dbname=$db_name;host=$db_host";

$predisScheme = 'tcp';
$predisPort = 6379;

function metric($message, $object, $method)
{
    echo $message . PHP_EOL;
    echo '<br>';
    $t = microtime(true);

    if ($method == 'get') {
        $data = $object->$method();
        echo '<b>' . substr((microtime(true) - $t), 0, 7) . '</b>сек.' . PHP_EOL;
        echo '<br>';
        echo 'example cache data: ' . substr($data, 0, 21);
        echo '<br>';
    } else {
        $object->$method();
        echo substr((microtime(true) - $t), 0, 7) . 'сек.' . PHP_EOL;
    }
    echo '<br>';
}

$mysqli = new MysqliCache($db_user, $db_pass, $db_host, $db_name, $tableName, $persistent);
metric("Время создания MysqliCache (persistent = $persistent) соединения: ", $mysqli, 'connect');
metric("Время сохранения MysqliCache: ", $mysqli, 'set');
metric("Время получения MysqliCache: ", $mysqli, 'get');

//$mysqli = new \cacheTest\MysqliCache('');
//metric ("Время создания MysqliCache соединения: ",$mysqli,'connect');
//metric ("Время сохранения MysqliCache: ",$mysqli,'set');
//metric ("Время получения MysqliCache: ",$mysqli,'get');
$mysqliPDO = new PdoMysqlCache($db_user, $db_pass, $db_host, $db_name, $tableName, $dsn, $persistent);
metric("Время создания PdoMysqlCache (persistent = $persistent) соединения: ", $mysqliPDO, 'connect');
metric("Время сохранения PdoMysqlCache: ", $mysqliPDO, 'set');
metric("Время получения PdoMysqlCache: ", $mysqliPDO, 'get');

$file = new FileCache();
metric("Время доступа к HDD(SSD): ", $file, 'connect');
metric("Время сохранения FileCache: ", $file, 'set');
metric("Время получения FileCache: ", $file, 'get');

$file = new PredisCache($predisScheme, $db_host, $predisPort);
metric("Время создания PredisCache соединения: ", $file, 'connect');
metric("Время сохранения PredisCache: ", $file, 'set');
metric("Время получения PredisCache: ", $file, 'get');

$file = new RedisCache($db_host, $predisPort, $persistent);
metric("Время создания RedisCache (persistent = $persistent) соединения: ", $file, 'connect');
metric("Время сохранения RedisCache: ", $file, 'set');
metric("Время получения RedisCache: ", $file, 'get');

//phpinfo();
