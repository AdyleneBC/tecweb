<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');

if (!empty($producto)) {
    $jsonOBJ = json_decode($producto, true);

    $nombre   = mysqli_real_escape_string($conexion, $jsonOBJ['nombre'] ?? '');
    $marca    = mysqli_real_escape_string($conexion, $jsonOBJ['marca'] ?? '');
    $modelo   = mysqli_real_escape_string($conexion, $jsonOBJ['modelo'] ?? '');
    $precio   = isset($jsonOBJ['precio']) && $jsonOBJ['precio'] !== '' ? (float)$jsonOBJ['precio'] : "NULL";
    $detalles = mysqli_real_escape_string($conexion, $jsonOBJ['detalles'] ?? '');
    $unidades = isset($jsonOBJ['unidades']) && $jsonOBJ['unidades'] !== '' ? (int)$jsonOBJ['unidades'] : "NULL";
    $imagen   = mysqli_real_escape_string($conexion, $jsonOBJ['imagen'] ?? '');

    // Verificar si el producto ya existe
    $sql_check = "SELECT id FROM productos 
                  WHERE eliminado = 0 AND ((nombre = '$nombre' AND marca = '$marca') OR (marca = '$marca' AND modelo = '$modelo'))";

    $result = $conexion->query($sql_check);

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "El producto ya existe en la base de datos."]);
    } else {
        $sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                       VALUES ('$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen', 0)";

        if ($conexion->query($sql_insert)) {
            echo json_encode(["success" => "Producto agregado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al insertar producto: " . $conexion->error]);
        }
    }
}

$conexion->close();
?>