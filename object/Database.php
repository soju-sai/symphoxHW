<?php

require __DIR__ . '/../config/config.php';

Class Database {
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
        } catch (Exception $ex) {
            echo "Connection error: " . $ex->getMessage();
        }

        return $this->conn;
    }
}

?>