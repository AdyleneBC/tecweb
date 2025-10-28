<?php
include_once __DIR__ . '/database.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['id'])) {
    echo json_encode(["error" => "Falta id"]);
    exit;
}
$id = intval($_POST['id']);

$sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen
        FROM productos
        WHERE id = {$id} AND eliminado = 0
        LIMIT 1";

if ($result = $conexion->query($sql)) {
    $row = $result->fetch_assoc();
    if ($row) {
        // normaliza a utf-8 por si la conexión no está en utf8mb4
        foreach ($row as $k => $v) {
            $row[$k] = is_string($v) ? utf8_encode($v) : $v;
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["error" => "No existe"]);
    }
    $result->free();
} else {
    echo json_encode(["error" => mysqli_error($conexion)]);
}
$conexion->close();
