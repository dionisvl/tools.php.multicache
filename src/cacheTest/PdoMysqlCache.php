<?php

namespace Multicache\cacheTest;

use PDO;

class PdoMysqlCache extends CacheTest implements CacheI
{
    private $dsn = "mysql:dbname=test;host=127.0.0.1";
    private $persistent_connect;
    private $db_user = 'root';
    private $db_pass = '';
    private $tableName = 'test';
    private $db_host = '127.0.0.1';
    private $db_name = 'test';
    private $connect;

    public function __construct($db_user, $db_pass, $db_host, $db_name, $tableName, $dsn, $persistent_connect = false)
    {
        if ($persistent_connect == true) {
            $this->persistent_connect = [PDO::ATTR_PERSISTENT => true];
        } else {
            $this->persistent_connect = [];
        }
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->tableName = $tableName;
        $this->dsn = $dsn;
        $this->test_data = $this->test_data();
    }

    private function check_pdo($sth)
    {
        if (!empty($sth->errorInfo()[2])) {
            dump('[' . __LINE__ . ']Произошла ошибка:');
            dump($sth->errorInfo()[2]);
            dump('Полная структура запроса:');
            dump($sth);
            $backtrace = debug_backtrace();
            dump($backtrace);
            die();
        }
    }

    public function connect()
    {
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] + $this->persistent_connect;
        $connect = new PDO(
            $this->dsn,
            $this->db_user,
            $this->db_pass,
            $options
        );
        $this->connect = $connect;
    }

    public function set()
    {
        $sql = "INSERT INTO `$this->tableName` (id,data)
          VALUES ('$this->id','$this->test_data')
          ON DUPLICATE KEY UPDATE data = '$this->test_data'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $this->check_pdo($stmt);
    }

    public function get()
    {
        $sql = "SELECT * FROM `$this->tableName` WHERE `id` = '$this->id'";
        $stmt = $this->connect->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {// $row - ассоциативный массив значений, ключи - названия столбцов
            return $row['data'];
        }
        return 'error PDO get';
    }

    public function __destruct()
    {
        if (!empty($this->persistent_connect)) {
            $this->connect = null;
        }
    }
}
