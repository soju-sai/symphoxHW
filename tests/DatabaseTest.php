<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../object/Database.php';

class DatabaseTest extends TestCase 
{
    public function testGetConnection() {
        $database = new Database();
        $db = $database->getConnection();
        // The connection error code should be zero, or there must be sth wrong with the connection.
        $this->assertEquals(0, $db->connect_errno);
    }
}
?>