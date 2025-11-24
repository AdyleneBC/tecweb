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

$app->post("/pruebapost", function( $request, $response, $args){
    $reqPost = $request->getParsedBody();
    $val1 = $reqPost["val1"];
    $val2 = $reqPost["val2"];
    
    $response->write( "Valores:" . $val1 . " " .$val2 );
    return $response;


});

$app->get("/testjson", function( $request, $response, $args){
    $data[0]["nombre"]="Sergio";
    $data[0]["apellidos"]="Rojas Espino";
    $data[1]["nombre"]="Adylene";
    $data[1]["apellidos"]="Baylon Cuahtlapantzi";
    $response->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response;
});

$app->run();