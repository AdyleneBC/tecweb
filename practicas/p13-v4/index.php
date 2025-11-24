<?php
require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// ajusta SOLO a tu base path real (o comenta si no usas base path)
$app->setBasePath("/proyectos/tecweb/practicas/p13-v4");

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hola Mundo Slim");
    return $response;
});

$app->get('/hola/{nombre}', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hola, " . $args['nombre']);
    return $response;
});

$app->post('/pruebapost', function (Request $request, Response $response, array $args) {
    $reqPost = $request->getParsedBody();
    $val1 = isset($reqPost['val1']) ? $reqPost['val1'] : '';
    $val2 = isset($reqPost['val2']) ? $reqPost['val2'] : '';

    $response->getBody()->write("Valores: " . $val1 . " " . $val2);
    return $response;
});

$app->get('/testjson', function (Request $request, Response $response, array $args) {
    $data = [];
    $data[0]['nombre'] = "Sergio";
    $data[0]['apellidos'] = "Rojas Espino";
    $data[1]['nombre'] = "Adylene";
    $data[1]['apellidos'] = "Baylon Cuahtlapantzi";

    $payload = json_encode($data, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
