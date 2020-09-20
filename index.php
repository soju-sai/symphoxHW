<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/object/Database.php';
require __DIR__ . '/object/URLConvertor.php';
require __DIR__ . '/object/Validation.php';
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

global $html;
$html = <<<HTML
    <html>
        <head>
            <title>縮網址</title>
        </head>
        <body>
            %s
        </body>
    </html>
HTML;

$app->get('/', function (Request $request, Response $response, $args) {

    $form = <<<FORM
        <form action="/create" method="get">
            <label for="url">請輸入URL於下方文字方塊，將為您產生縮網址：</label><br>
            <input type="text" id="url" name="url" size="50"><br>
            <input type="submit" value="Submit">
        </form>
        FORM;
    $html = sprintf($GLOBALS['html'], $form);
    $response->getBody()->write($html);
    return $response;
});

$app->get('/create', function (Request $request, Response $response, $args) {

    // TODO: try catch
    
    $uri = $request->getUri()->getQuery();

    $validation = new Validation();
    // Validate if the input uri fits a reasonable uri
    $url = $validation->validateUri($uri);
    if (!$url) {
        $html = sprintf($GLOBALS['html'], "<p>不是有效的網址!</p>");
        $response->getBody()->write($html);
        return $response;
    }

    $database = new Database();
    $db = $database->getConnection();

    $urlCvt = new URLConvertor($db);
    $shortCode = $urlCvt->generateShortCode($url);

    $db->close(); // close db connection

    if (!$shortCode) {
        $html = sprintf($GLOBALS['html'], "<p>產生短網址錯誤，請再試一次</p>");
        $response->getBody()->write($html);
        return $response;
    }

    $html = sprintf($GLOBALS['html'], "<p>產生的短網址: $shortCode</p>");
    $response->getBody()->write($html);
    return $response;
});

// redirect shorten-url to url
$app->get('/{name}', function (Request $request, Response $response, $args) {

    $shortCode = $args['name'];

    // TODO: prevent sql injection

    $database = new Database();
    $db = $database->getConnection();

    $urlCvt = new URLConvertor($db);

    // TODO: validate if the input fit shorten-url format

    $url = $urlCvt->redirectURL($shortCode);

    $db->close(); // close db connection

    if (!$url) {
        $html = sprintf($GLOBALS['html'], "短網址不存在: $shortCode");
        $response->getBody()->write($html);
        return $response;
    }
    
    return $response
    ->withHeader('Location',$url )
    ->withStatus(302);
});

$app->run();
?>