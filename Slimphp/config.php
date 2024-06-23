<?php
// class db
// {
//     // Properties
//     private $host = 'localhost';
//     private $user = 'root';
//     private $password = '';
//     private $dbname = 'wt_labtest3';

//     // Connect
//     function connect()
//     {
//         $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname";
//         $dbConnection = new PDO($mysql_connect_str, $this->user, $this->password);
//         $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         return $dbConnection;
//     }
// }

class Database {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $db   = 'webtech_labtest';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}
