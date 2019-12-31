<?php

namespace Multicache\cacheTest;

class MysqliCache extends CacheTest implements CacheI
{
    private $persistent_connect;
    private $db_user = 'root';
    private $db_pass = '';
    private $tableName = 'test';
    private $db_host = '127.0.0.1';
    private $db_name = 'test';
    private $connect;

    public function __construct($db_user, $db_pass, $db_host, $db_name, $tableName, $persistent_connect = false)
    {
        if ($persistent_connect == true) {
            $this->persistent_connect = 'p:';
        }
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->tableName = $tableName;
        $this->test_data = $this->test_data();
    }

    public function connect()
    {
        $mysqli = new \mysqli(
            $this->persistent_connect . $this->db_host,
            $this->db_user,
            $this->db_pass,
            $this->db_name
        );
        $this->connect = $mysqli;
    }

    public function set()
    {
        $sql = "INSERT INTO `$this->tableName` (id,data)
          VALUES ('$this->id','$this->test_data')
          ON DUPLICATE KEY UPDATE data = '$this->test_data'";
//        $sql = "INSERT INTO `$this->tableName` (data)
//          VALUES ($test_data)";

        if (!$result = $this->connect->query($sql)) {
            echo "Извините, возникла проблема в работе сайта.";
            echo "Ошибка: Наш запрос не удался и вот почему: \n";
            echo "Запрос: " . $sql . "\n";
            echo "Номер ошибки: " . $this->connect->errno . "\n";
            echo "Ошибка: " . $this->connect->error . "\n";
            exit;
        }
    }

    public function get()
    {
        $sql = "SELECT * FROM `$this->tableName` WHERE `id` = '$this->id'";
        if (!$result = $this->connect->query($sql)) {
            return 'Mysqli error';
        } else {
            $row = $result->fetch_assoc();
            return $row['data'];
        }
    }

    public function __destruct()
    {
        if (!empty($this->persistent_connect)) {
            $this->connect->close();
        }
    }
}
