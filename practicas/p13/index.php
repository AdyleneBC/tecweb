<?php 
require 'vendor/autoload.php';

$app = new Slim\App();

$app->get('/', function($request, $response, $args ){
    $response->write("Hola Mundo Slim");
    return $response;
});

$app->get("/hola/{nombre}", function($request, $response, $args ){
    $response->write("Hola, " . $args["nombre"]);
    return $response;
});
//ldkkdkd
$app->run();