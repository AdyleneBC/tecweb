<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
    $data = array();

    /** SE CREA EL OBJETO DE CONEXION */
    @$link = new mysqli('localhost', 'admin_tw', 'adylene', 'marketzone');
    if ($link->connect_errno) {
        die('Falló la conexión: '.$link->connect_error.'<br/>');
    }
    $link->set_charset('utf8mb4');

    /** SOLO productos vigentes */
    $sql = "SELECT * FROM productos WHERE eliminado = 0";

    if ($result = $link->query($sql)) {
        $row = $result->fetch_all(MYSQLI_ASSOC);

        // Normaliza a UTF-8 si lo necesitas
        foreach ($row as $num => $registro) {
            foreach ($registro as $key => $value) {
                $data[$num][$key] = is_string($value) ? utf8_encode($value) : $value;
            }
        }
        $result->free();
    }

    $link->close();
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Productos vigentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <h3>PRODUCTOS VIGENTES</h3>
    <br/>

    <?php if (!empty($row)) : ?>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Detalles</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Eliminado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($row as $value) : ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($value['id']) ?></th>
                    <td><?= htmlspecialchars($value['nombre']) ?></td>
                    <td><?= htmlspecialchars($value['marca']) ?></td>
                    <td><?= htmlspecialchars($value['modelo']) ?></td>
                    <td><?= htmlspecialchars($value['precio']) ?></td>
                    <td><?= htmlspecialchars($value['unidades']) ?></td>
                    <td><?= htmlspecialchars($value['detalles']) ?></td>
                    <td><img src="<?= htmlspecialchars($value['imagen']) ?>" alt="" style="max-height:80px"></td>
                    <td><?= htmlspecialchars($value['eliminado']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <div class="alert alert-info" role="alert">
            No hay productos vigentes para mostrar.
        </div>
    <?php endif; ?>
</body>
</html>
