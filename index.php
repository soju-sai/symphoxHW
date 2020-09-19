<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/object/Database.php';
require __DIR__ . '/object/URLConvertor.php';
require __DIR__ . '/object/Validation.php';
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/create', function (Request $request, Response $response, $args) {

    $uri = $request->getUri()->getQuery();
    
    $validation = new Validation();
    $url = $validation->validate($uri);
    if (!$url) {
        $response->getBody()->write("不是有效的網址!");
        return $response;
    }

    $database = new Database();
    $db = $database->getConnection();

    $urlCvt = new URLConvertor($db);
    $shortCode = $urlCvt->generateShortCode($url);

    $response->getBody()->write("產生的短網址: " . $shortCode);
    return $response;
});

// redirect shorten-url to url
$app->get('/{name}', function (Request $request, Response $response, $args) {

    $shortCode = $args['name'];
    $database = new Database();
    $db = $database->getConnection();

    $urlCvt = new URLConvertor($db);
    $url = $urlCvt->redirectURL($shortCode);

    if (!$url) {
        $response->getBody()->write("短網址不存在: " . $shortCode);
        return $response;
    }
    
    return $response
    ->withHeader('Location',$url )
    ->withStatus(302);
});

$app->run();
