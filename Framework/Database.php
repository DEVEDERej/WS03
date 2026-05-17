<?php

namespace Framework;

use PDO;
use PDOException;
use Exception;

class Database
{
    public $conn;

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']}; port={$config['port']}; dbname={$config['dbname']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];
        
        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: {$e->getMessage()}");
        }
    }

    public function Query($Query, $params = [])
    {
        try {
            $sth = $this->conn->prepare($Query);
            
            // Bind parameters if provided
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $sth->bindValue($key, $value);
                }
            }
            
            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            throw new Exception("Query execution failed: {$e->getMessage()}");
        }
    }
}
