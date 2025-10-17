<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();
    // SE VERIFICA HABER RECIBIDO ALGÚN PARÁMETRO
    if (isset($_POST['id']) || isset($_POST['nombre']) || isset($_POST['marca']) || isset($_POST['detalles'])) {
        // Obtener los valores de los parámetros y agregar el comodín "%" para el LIKE
        $id = isset($_POST['id']) ? $conexion->real_escape_string($_POST['id']) : '';
        $nombre = isset($_POST['nombre']) ? "%" . $conexion->real_escape_string($_POST['nombre']) . "%" : '';
        $marca = isset($_POST['marca']) ? "%" . $conexion->real_escape_string($_POST['marca']) . "%" : '';
        $detalles = isset($_POST['detalles']) ? "%" . $conexion->real_escape_string($_POST['detalles']) . "%" : '';

        // Construcción de la consulta de búsqueda
        $query = "SELECT * FROM productos WHERE id='$id' or nombre LIKE '$nombre' or marca LIKE '$marca' or detalles LIKE '$detalles'";
        // Ejecutar la consulta
        if ($result = $conexion->query($query)) {
            // Verificamos si hay resultados
            while ($row = $result->fetch_assoc()) {
                // Agregar cada producto al arreglo de respuesta
                $data[] = $row;
            }
			$result->free();
		} else {
            die('Query Error: '.mysqli_error($conexion));
        }
		$conexion->close();
    } 
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>