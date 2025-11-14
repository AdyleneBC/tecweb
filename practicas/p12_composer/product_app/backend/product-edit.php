<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Update\Update;

$post = [
    'id'       => $_POST['id']       ?? 0,
    'nombre'   => $_POST['nombre']   ?? '',
    'marca'    => $_POST['marca']    ?? '',
    'modelo'   => $_POST['modelo']   ?? '',
    'precio'   => $_POST['precio']   ?? 0,
    'unidades' => $_POST['unidades'] ?? 0,
    'detalles' => $_POST['detalles'] ?? '',
    'imagen'   => $_POST['imagen']   ?? ''
];

$prodObj = new Update('marketzone');
$prodObj->edit($post);
echo $prodObj->getData();
