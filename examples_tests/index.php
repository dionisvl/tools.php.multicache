<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';

use Multicache\cacheTest\MysqliCache;
use Multicache\cacheTest\PdoMysqlCache;
use Multicache\cacheTest\FileCache;
use Multicache\cacheTest\PredisCache;
use Multicache\cacheTest\RedisCache;

$db_user = getParam('DB_USERNAME=',"/var/www/$_SERVER[HTTP_HOST]/html/.env");
$db_pass = getParam('DB_PASSWORD=',"/var/www/$_SERVER[HTTP_HOST]/html/.env");
$db_host = '127.0.0.1';
$db_name = 'test';
$tableName = 'test';
if (empty($_REQUEST['persistent'])) {
    $persistent = 0;
} else {
    $persistent = (int)$_REQUEST['persistent'];
}

$dsn = "mysql:dbname=$db_name;host=$db_host";

$predisScheme = 'tcp';
$redisPort = 6379;
$redisPass = 'Jc9pHsN+NVkoTraEsqIW8EXBIC2hjHxwu2wJxa8EiSNl58B0+THXTk6GZw7g6Vx/E5pcwDMQF57a9far';

function metric($message, $object, $method)
{
    echo $message . PHP_EOL;
    echo '<br>';
    $t = microtime(true);

    if ($method == 'get') {
        $data = $object->$method();
        echo '<b>' . substr((microtime(true) - $t), 0, 7) . '</b>сек.' . PHP_EOL;
        echo '<br>';
        echo 'example cache data: ' . substr($data, 0, 21) . '...(' . round(strlen($data) / 1024, 4), 'kbytes)';
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

$file = new PredisCache($predisScheme, $db_host, $redisPort, $persistent, $redisPass);
metric("Время создания PredisCache (persistent = $persistent) соединения: ", $file, 'connect');
metric("Время сохранения PredisCache: ", $file, 'set');
metric("Время получения PredisCache: ", $file, 'get');

$file = new RedisCache($db_host, $redisPort, $persistent, $redisPass);
metric("Время создания RedisCache (persistent = $persistent) соединения: ", $file, 'connect');
metric("Время сохранения RedisCache: ", $file, 'set');
metric("Время получения RedisCache: ", $file, 'get');

$file = new FileCache();
metric("Время доступа к HDD(SSD): ", $file, 'connect');
metric("Время сохранения FileCache: ", $file, 'set');
metric("Время получения FileCache: ", $file, 'get');
//phpinfo();
