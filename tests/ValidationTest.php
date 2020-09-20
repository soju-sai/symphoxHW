<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../object/Validation.php';

class ValidationTest extends TestCase 
{
    // test valid cases
    public function testValidateUri() {
        $validation = new Validation();
        $uri = 'url=http%3A%2F%2Fwww.slimframework.com%2Fdocs%2Fv4%2Fobjects%2Frequest.html';
        $url = $validation->validateUri($uri);
        $this->assertEquals('http://www.slimframework.com/docs/v4/objects/request.html', $url);
    }

    /**
     * Test invalid cases
     * @dataProvider provider
     */
    public function testInValidatedUri($uri) {
        $validation = new Validation();
        $url = $validation->validateUri($uri);
        $this->assertEquals(false, $url);
    }

    public function provider() {
        return [
            ['abcd'],
            ['url=http://www.example.c\om/'],
            ['']
        ];
    }
}
?>