<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../object/Database.php';
require __DIR__ . '/../object/URLConvertor.php';

class URLConvertorTest extends TestCase 
{
    private $db;
    private $table_name;
    private $urlCvt;

    protected function setUp(): void
    {
        $this->table_name = 'test_url_shorten';
        $database = new Database();
        $this->db = $database->getConnection();
        $this->db->query('INSERT INTO ' . $this->table_name . ' (url, short_code) VALUES ("https://phpunit.readthedocs.io/", "1b2c3a");');
        $this->urlCvt = new URLConvertor($this->db, $this->table_name);
    }

    public function testGenerateShortCode() {        
        // url already existed case
        $url = 'https://phpunit.readthedocs.io/';
        $shortCode = $this->urlCvt->generateShortCode($url);
        $this->assertSame('1b2c3a', $shortCode);

        // url is not exist case, will return a hexadecimal random short code with length 6
        $url = 'http://www.slimframework.com/';
        $shortCode = $this->urlCvt->generateShortCode($url);
        $this->assertStringMatchesFormat('%x', $shortCode);
        $this->assertStringMatchesFormat(6, strlen($shortCode));
    }

    public function testRedirectURL() {
        // shortCode exists case
        $url = $this->urlCvt->redirectURL("1b2c3a");
        $this->assertEquals('https://phpunit.readthedocs.io/', $url);

        // shortCode is not exist case
        $this->assertSame(FALSE, $this->urlCvt->redirectURL("3a1b2c"));
    }

    protected function tearDown(): void
    {
        $this->db->query('TRUNCATE ' . $this->table_name . ';');
        $this->db->close();
    }
}
?>