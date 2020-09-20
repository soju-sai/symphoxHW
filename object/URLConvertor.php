<?php

Class URLConvertor {
    // database connection and table url_shorten
    private $conn;
    private $table_name;

    public function __construct($db, $table_name = "url_shorten") {
        $this->conn = $db;
        $this->table_name = $table_name;
    }

    public function generateShortCode($url) {

        // check if url already exists, use the existed url's shortCode
        $result = $this->conn->query('SELECT short_code FROM ' . $this->table_name . ' WHERE url = "' . $url . '"');
        if ($result && $result->num_rows > 0) {
            return $result->fetch_object()->short_code;
        }

        $shortCode = $this->generateRandomCode();

        $result = $this->conn->query('INSERT INTO ' . $this->table_name . ' (url, short_code) VALUES ("' .$url . '", "' . $shortCode . '")');

        if ($result == FALSE) {
            return FALSE;
        }

        return $shortCode;
    }

    private function generateRandomCode() {
        $randomCode = substr(md5(uniqid(rand(), true)),0,6);

        // check if randomCode already exists in shortCode column
        $result = $this->conn->query('SELECT short_code FROM ' . $this->table_name . ' WHERE short_code = ' . $randomCode);

        // if shortCode existed then generate another shortcode
        if ($result && $result->num_rows > 0) {
            $randomCode = generateShortCode();
        }

        return $randomCode;
    }

    public function redirectURL($shortCode) {
        // check if shortCode exists
        $result = $this->conn->query('SELECT url FROM ' . $this->table_name . ' WHERE short_code = "' . $shortCode. '"');

        if ($result && $result->num_rows > 0) {
            return $result->fetch_object()->url;
        } else {
            return FALSE;
        }
    }
}

?>