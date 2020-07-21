<?php

namespace App\Db;

use \PDO;

class Connection {
    private static $instance = null;
    private $connection;

    function __construct() {
        $this->connection = new PDO('sqlite::memory:');
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Connection();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}