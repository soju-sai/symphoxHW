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
        try {
            // check if url already exists, use the existed url's shortCode
            $stmt = $this->conn->prepare('SELECT short_code FROM ' . $this->table_name . ' WHERE url = ?');
            $stmt->bind_param("s", $url);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                return $result->fetch_object()->short_code;
            }

            $shortCode = $this->generateRandomCode();

            $stmt = $this->conn->prepare('INSERT INTO ' . $this->table_name . ' (url, short_code) VALUES (?, ?)');
            $stmt->bind_param("ss", $url, $shortCode);
            $stmt->execute();
            $result = $stmt->insert_id;

            if ($result == FALSE) {
                return FALSE;
            }
    
            return $shortCode;

        } catch (Exception $e) {
            $this->conn->close(); // close db connection
            error_log($e);
            throw $e;
        }
    }

    private function generateRandomCode() {
        try {
            $randomCode = substr(md5(uniqid(rand(), true)),0,6);

            // check if randomCode already exists in shortCode column
            $result = $this->conn->query('SELECT short_code FROM ' . $this->table_name . ' WHERE short_code = ' . $randomCode);

            // if shortCode existed then generate another shortcode
            if ($result && $result->num_rows > 0) {
                $randomCode = generateShortCode();
            }

            return $randomCode;
        } catch (Exception $e) {
            $this->conn->close(); // close db connection
            error_log($e);
            throw $e;
        }
    }

    public function redirectURL($shortCode) {
        try {
            // check if shortCode exists
            $stmt = $this->conn->prepare('SELECT url FROM ' . $this->table_name . ' WHERE short_code = ?');
            $stmt->bind_param("s", $shortCode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_object()->url;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            $this->conn->close(); // close db connection
            error_log($e);
            throw $e;
        }
    }
}

?>