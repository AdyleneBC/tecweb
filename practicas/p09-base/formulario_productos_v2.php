<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
      ol, ul { 
      list-style-type: none;
      }
    </style>
    <title>Formulario</title>
</head>
<body>
    <h1>Datos de la tabla "productos"</h1>

    <form id="miFormulario" action="http://localhost/proyectos/tecweb/practicas/p09-base/update_producto.php" method="post">
        <fieldset>
            <legend>Actualiza los datos de los productos:</legend>
            <ul>
                <li>
                    <label>Id:</label>
                    <input type="text" name="id" value="<?= $_POST['id'] ?>" readonly />
                </li>
                <li><label>Nombre:</label> 
                    <input type="text" name="nombre" value="<?= isset($_POST['nombre']) ? $_POST['nombre'] : (isset($_GET['nombre']) ? $_GET['nombre'] : '') ?>">
                </li>
                <li>
                    <label>Marca:</label> 
                    <select name="marca">
                        <option value="">Seleccione una marca</option>
                        <option value="nike" <?= (isset($_POST['marca']) && $_POST['marca'] == "nike") || (isset($_GET['marca']) && $_GET['marca'] == "nike") ? 'selected' : '' ?>>nike</option>
                        <option value="adidas" <?= (isset($_POST['marca']) && $_POST['marca'] == "adidas") || (isset($_GET['marca']) && $_GET['marca'] == "adidas") ? 'selected' : '' ?>>adidas</option>
                        <option value="salomon" <?= (isset($_POST['marca']) && $_POST['marca'] == "salomon") || (isset($_GET['marca']) && $_GET['marca'] == "salomon") ? 'selected' : '' ?>>salomon</option>
                        <option value="reebok" <?= (isset($_POST['marca']) && $_POST['marca'] == "reebok") || (isset($_GET['marca']) && $_GET['marca'] == "reebok") ? 'selected' : '' ?>>reebok</option>
                    </select>
                </li>
                <li><label>Modelo:</label> 
                    <input type="text" name="modelo" value="<?= isset($_POST['modelo']) ? $_POST['modelo'] : (isset($_GET['modelo']) ? $_GET['modelo'] : '') ?>">
                </li>
                <li><label>Precio:</label> 
                    <input type="number" name="precio" step="0.01" value="<?= isset($_POST['precio']) ? $_POST['precio'] : (isset($_GET['precio']) ? $_GET['precio'] : '') ?>">
                </li>
                <li><label>Unidades:</label> 
                    <input type="number" name="unidades" value="<?= isset($_POST['unidades']) ? $_POST['unidades'] : (isset($_GET['unidades']) ? $_GET['unidades'] : '') ?>">
                </li>
                <li><label>Detalles:</label> 
                    <textarea name="detalles"><?= isset($_POST['detalles']) ? $_POST['detalles'] : (isset($_GET['detalles']) ? $_GET['detalles'] : '') ?></textarea>
                </li>
                <li><label>Imagen:</label> 
                    <input type="text" name="imagen" value="<?= isset($_POST['imagen']) ? $_POST['imagen'] : (isset($_GET['imagen']) ? $_GET['imagen'] : '') ?>">
                </li>
                <li><label>Eliminado:</label> 
                    <input type="number" name="eliminado" value="<?= isset($_POST['eliminado']) ? $_POST['eliminado'] : (isset($_GET['eliminado']) ? $_GET['eliminado'] : '') ?>">
                </li>
            </ul>
        </fieldset>
        <p>
            <input type="submit" value="ENVIAR">
        </p>
    </form>
   <script>
        document.getElementById("formulario").addEventListener("submit", function (event) {
            event.preventDefault();

            const DEFAULT_IMAGE = "img/imagen.png";

            const nombre = document.getElementById("form-name").value;
            const marca = document.getElementById("form-marca").value;
            const modelo = document.getElementById("form-modelo").value;
            const precioV = document.getElementById("form-precio").value;
            const precio = parseFloat(precioV);
            const detalles = document.getElementById("form-detalles").value;
            const unidadesV = document.getElementById("form-unidades").value;
            const unidades = parseInt(unidadesV, 10);
            const imagenEl = document.getElementById("form-imagen");
            let imagen = imagenEl.value.trim();

            // a) Nombre
            if (!nombre) { alert("El nombre es requerido."); return; }
            if (nombre.length > 100) { alert("El nombre debe tener 100 caracteres o menos."); return; }

            // b) Marca
            if (!marca) { alert("La marca es requerida."); return; }

            // c) Modelo
            if (!modelo) { alert("El modelo es requerido."); return; }
            if (!/^[A-Za-z0-9]+$/.test(modelo)) { alert("El modelo debe ser alfanumérico."); return; }
            if (modelo.length > 25) { alert("El modelo debe tener 25 caracteres o menos."); return; }

            // d) Precio
            if (precioV === "" || isNaN(precio)) { alert("El precio es requerido."); return; }
            if (precio <= 99.99) { alert("El precio debe ser mayor a 99.99."); return; }

            // e) Detalles (opcional)
            if (detalles.length > 250) { alert("Los detalles no pueden exceder 250 caracteres."); return; }

            // f) Unidades
            if (unidadesV === "" || isNaN(unidades)) { alert("Las unidades son requeridas."); return; }
            if (unidades < 0) { alert("Las unidades deben ser 0 o más."); return; }

            // g) Imagen (opcional con valor por defecto para el envío)
            if (!imagen) {
                imagenEl.value = DEFAULT_IMAGE; // <-- se envía al servidor este valor
            }

            // ¡Ahora sí! Enviar el formulario
            this.submit();
        });
    </script>
</body>

</html>