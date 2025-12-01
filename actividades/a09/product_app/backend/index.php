<?php
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// cargar clases
require_once __DIR__ . '/myapi/DataBase.php';
require_once __DIR__ . '/myapi/CREATE/Create.php';
require_once __DIR__ . '/myapi/READ/Read.php';
require_once __DIR__ . '/myapi/UPDATE/Update.php';
require_once __DIR__ . '/myapi/DELETE/Delete.php';

// usar namespaces reales
use MYAPI\Read\Read;
use MYAPI\Create\Create;
use MYAPI\Update\Update;
use MYAPI\Delete\Delete;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->setBasePath('/proyectos/tecweb/actividades/a09/product_app/backend');
$app->addRoutingMiddleware();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// GET /products  -> lista productos
$app->get('/products', function (Request $request, Response $response) {
    $read = new Read("marketzone");
    $read->list();
    $response->getBody()->write(json_encode($read->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// GET /product/{id} -> un producto
$app->get('/product/{id}', function (Request $request, Response $response, array $args) {
    $read = new Read("marketzone");
    $read->single($args['id']);
    $response->getBody()->write(json_encode($read->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// GET /products/{search} -> buscar
$app->get('/products/{search}', function (Request $request, Response $response, array $args) {
    $read = new Read("marketzone");
    $read->search($args['search']);
    $response->getBody()->write(json_encode($read->getData()));
    return $response->withHeader('Content-Type', 'application/json');
});

// POST /product
$app->post('/product', function (Request $request, Response $response) {
    $params = (array)$request->getParsedBody();

    // usar tu clase real
    $create = new \MYAPI\Create\Create('marketzone');
    $create->add($params);

    // devolver lo que tu clase guardó en $this->data
    $resp = $create->getData();

    $response->getBody()->write($resp);
    return $response->withHeader('Content-Type', 'application/json');
});


// PUT /product
$app->put('/product', function (Request $request, Response $response) {
    $params = (array)$request->getParsedBody();

    $update = new \MYAPI\Update\Update('marketzone');
    $update->edit($params);

    $resp = $update->getData();
    $response->getBody()->write($resp);
    return $response->withHeader('Content-Type', 'application/json');
});


// DELETE /product
$app->delete('/product', function (Request $request, Response $response) {
    $params = (array)$request->getParsedBody();
    $id = $params['id'] ?? 0;

    $delete = new \MYAPI\Delete\Delete('marketzone');
    $delete->delete($id);

    $resp = $delete->getData();
    $response->getBody()->write($resp);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
