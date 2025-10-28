<?php
include_once __DIR__ . '/database.php';
header('Content-Type: application/json; charset=utf-8');

$body = file_get_contents('php://input');
$p = json_decode($body, true);
if (!$p || !isset($p['id']) || !isset($p['nombre'])) {
    echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
    exit;
}

$id       = intval($p['id']);
$nombre   = $p['nombre'];
$marca    = $p['marca']    ?? 'NA';
$modelo   = $p['modelo']   ?? 'NA';
$detalles = $p['detalles'] ?? 'NA';
$imagen   = $p['imagen']   ?? 'img/default.png';
$precio   = isset($p['precio'])   ? floatval($p['precio'])   : 0.0;
$unidades = isset($p['unidades']) ? intval($p['unidades'])   : 1;

$sql = "UPDATE productos SET
          nombre=?, marca=?, modelo=?, precio=?, detalles=?, unidades=?, imagen=?
        WHERE id=? AND eliminado=0";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssdsisi",
    $nombre,
    $marca,
    $modelo,
    $precio,
    $detalles,
    $unidades,
    $imagen,
    $id
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Producto actualizado"], JSON_PRETTY_PRINT);
} else {
    echo json_encode(["status" => "error", "message" => "ERROR: " . $conexion->error], JSON_PRETTY_PRINT);
}
$stmt->close();
$conexion->close();
