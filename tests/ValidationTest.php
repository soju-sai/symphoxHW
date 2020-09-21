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
     * Test valid url cases
     * @dataProvider validateUrlProvider
     */
    public function testValidatedUrl($url) {
        $validation = new Validation();
        $url = $validation->validateUrl($url);
        $this->assertEquals(true, $url);
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

    public function validateUrlProvider() {
        return [
            ['https://github.com/soju-sai/symphoxHW'],
            ['http://ottogi.qqbuy.com.tw/?_qrand=41424.8911964699'],
            ['http://clean0074.pixnet.net/album/photo/35263406-%e7%82%b8'],
            ['http://www.slimframework.com/docs/v4/objects/request.html'],
            ['https://cookbiz.jp/job_search/?cook=biz&la=9&pc=148&em=2&gr=54']
        ];
    }

    public function invalidShortCodeProvider() {
        return [
            ['abc12'],
            ['abcde12'],
            ['b8543A'],
            ['d6=~12'],
            ['#$%&12'],
            ['"343"b'],
            ['5f7ed)']
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