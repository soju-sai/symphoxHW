<?php

Class URLConvertor {
    // database connection and table url_shorten
    private $conn;
    private $table_name = "url_shorten";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateShortCode($url) {

        // check if url already exists, use the existed url's shortCode
        $result = $this->conn->query('SELECT short_code FROM url_shorten WHERE url = "' . $url . '"');
        if ($result && $result->num_rows > 0) {
            return $result->fetch_object()->short_code;
        }

        $shortCode = $this->generateRandomCode();

        $result = $this->conn->query('INSERT INTO url_shorten (url, short_code) VALUES ("' .$url . '", "' . $shortCode . '")');

        if ($result == FALSE) {
            die("資料寫入不成功");
        }
        // var_dump($result->fetch_object());

        return $shortCode;
    }

    private function generateRandomCode() {
        $randomCode = substr(md5(uniqid(rand(), true)),0,6);

        // check if randomCode already exists in shortCode column
        $result = $this->conn->query('SELECT short_code FROM url_shorten WHERE short_code = ' . $randomCode);

        // if shortCode existed then generate another shortcode
        if ($result && $result->num_rows > 0) {
            $randomCode = generateShortCode();
        }

        return $randomCode;
    }

    public function redirectURL($shortCode) {
        // check if shortCode exists
        $result = $this->conn->query('SELECT url FROM url_shorten WHERE short_code = ' . $shortCode);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_object()->url;
        } else {
            return FALSE;
        }
    }
}

?>