<?php
namespace App\Core;

use Config\Database as DBConfig;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = new DBConfig();
        $this->pdo = $config->getConnection();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>