<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../object/Validation.php';

class ValidationTest extends TestCase 
{
    // test valid url cases
    public function testSanitizeUri2Url() {
        $validation = new Validation();
        $url = 'urlToCode=http%3A%2F%2Fwww.slimframework.com%2Fdocs%2Fv4%2Fobjects%2Frequest.html';
        $url = $validation->sanitizeUri2Url($url);
        $this->assertEquals('http://www.slimframework.com/docs/v4/objects/request.html', $url);

        $url = $validation->sanitizeUri2Url($url);
        $url = 'http://www.slimframework.com/docs/v4/objects/request.html';
        $url = $validation->sanitizeUri2Url($url);
        $this->assertEquals(false, $url);
    }

    /**
     * Test invalid url cases
     * @dataProvider invalidatedUrlProvider
     */
    public function testInValidatedUrl($url) {
        $validation = new Validation();
        $url = $validation->validateUrl($url);
        $this->assertEquals(false, $url);
    }

    /**
     * Test invalid short code cases
     * @dataProvider invalidShortCodeProvider
     */
    public function testInValidateShortCode($shortCode) {
        $validation = new Validation();
        $isOK = $validation->validateShortCode($shortCode);
        $this->assertEquals(false, $isOK);
    }

    public function invalidShortCodeProvider() {
        return [
            ['abc12'],
            ['abcde12'],
            ['b8543A'],
            ['d6=~12'],
            ['#$%&12'],
            ['"343"b'],
            ['5f7ed)'],
        ];
    }

    public function invalidatedUrlProvider() {
        return [
            ['abcd'],
            ['url=http://www.example.c\om/'],
            ['']
        ];
    }
}
?>