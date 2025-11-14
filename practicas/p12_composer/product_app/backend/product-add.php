<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Create\Create;

$post = [
    'nombre'   => $_POST['nombre']   ?? '',
    'marca'    => $_POST['marca']    ?? '',
    'modelo'   => $_POST['modelo']   ?? '',
    'precio'   => $_POST['precio']   ?? 0,
    'unidades' => $_POST['unidades'] ?? 0,
    'detalles' => $_POST['detalles'] ?? '',
    'imagen'   => $_POST['imagen']   ?? ''
];

$prodObj = new Create('marketzone');
$prodObj->add($post);
echo $prodObj->getData();
