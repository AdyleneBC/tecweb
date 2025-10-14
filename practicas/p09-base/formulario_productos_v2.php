<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        ol,
        ul {
            list-style-type: none;
        }
    </style>
    <title>Formulario-Sneakers</title>
</head>

<body>
    <h1>Datos de la tabla "productos"</h1>

    <form id="formulario" action="http://localhost:81/proyectos/tecweb/practicas/p09-base/update_producto.php" method="post">
        <fieldset>
            <legend>Actualición de los datos del producto seleccionado:</legend>
            <ul>
                <li>
                    <label>Id:</label>
                    <input type="text" name="id" value="<?= $_POST['id'] ?>" readonly>
                </li>
                <li><label>Nombre:</label>
                    <input type="text" id="id_nombre" name="nombre" value="<?= isset($_POST['nombre']) ? $_POST['nombre'] : (isset($_GET['nombre']) ? $_GET['nombre'] : '') ?>">
                </li>
                <li>
                    <label>Marca:</label>
                    <select name="marca" id="id_marca">
                        <option value="">Marca:</option>
                        <option value="nike" <?= (isset($_POST['marca']) && $_POST['marca'] == "nike") || (isset($_GET['marca']) && $_GET['marca'] == "nike") ? 'selected' : '' ?>>Nike</option>
                        <option value="adidas" <?= (isset($_POST['marca']) && $_POST['marca'] == "adidas") || (isset($_GET['marca']) && $_GET['marca'] == "adidas") ? 'selected' : '' ?>>Adidas</option>
                        <option value="salomon" <?= (isset($_POST['marca']) && $_POST['marca'] == "salomon") || (isset($_GET['marca']) && $_GET['marca'] == "salomon") ? 'selected' : '' ?>>Salomon</option>
                        <option value="reebok" <?= (isset($_POST['marca']) && $_POST['marca'] == "reebok") || (isset($_GET['marca']) && $_GET['marca'] == "reebok") ? 'selected' : '' ?>>Reebok</option>
                    </select>
                </li>
                <li><label>Modelo:</label>
                    <input type="text" id="id_modelo" name="modelo" value="<?= isset($_POST['modelo']) ? $_POST['modelo'] : (isset($_GET['modelo']) ? $_GET['modelo'] : '') ?>">
                </li>
                <li><label>Precio:</label>
                    <input type="number" id="id_precio" name="precio" value="<?= isset($_POST['precio']) ? $_POST['precio'] : (isset($_GET['precio']) ? $_GET['precio'] : '') ?>">
                </li>
                <li><label>Unidades:</label>
                    <input type="number" id="id_unidades" name="unidades" value="<?= isset($_POST['unidades']) ? $_POST['unidades'] : (isset($_GET['unidades']) ? $_GET['unidades'] : '') ?>">
                </li>
                <li><label>Detalles:</label>
                    <textarea name="detalles" id="id_detalles"><?= isset($_POST['detalles']) ? $_POST['detalles'] : (isset($_GET['detalles']) ? $_GET['detalles'] : '') ?></textarea>
                </li>
                <li><label>Imagen:</label>
                    <input type="text" name="imagen" id="id_imagen" value="<?= isset($_POST['imagen']) ? $_POST['imagen'] : (isset($_GET['imagen']) ? $_GET['imagen'] : '') ?>">
                </li>
                <li><label>Eliminado:</label>
                    <input type="number" name="eliminado" value="<?= isset($_POST['eliminado']) ? $_POST['eliminado'] : (isset($_GET['eliminado']) ? $_GET['eliminado'] : '') ?>" readonly>
                </li>
            </ul>
        </fieldset>
        <p>
            <input type="submit" value="ENVIAR">
        </p>
    </form>
    <script>

      document.getElementById("formulario").addEventListener("submit", function(event) {
          event.preventDefault();

          const DEFAULT_IMAGE = "src/imagen.png";
          
          let nombre = document.getElementById("id_nombre").value.trim();
          let marca = document.getElementById("id_marca").value;
          let modelo = document.getElementById("id_modelo").value.trim();
          let precio = parseFloat(document.getElementById("id_precio").value);
          let detalles = document.getElementById("id_detalles").value.trim();
          let unidades = parseInt(document.getElementById("id_unidades").value);
          const imagenEl = document.getElementById("id_imagen");
            let imagen = imagenEl.value.trim();
          
          if (!nombre) {
              alert("El nombre es requerido.");
              return;
          }
          if(nombre.length>100){
            alert("El nombre tener 100 o menos caracteres.")
            return;
          }
          if (!marca) {
              alert("La marca es requerida.");
              return;
          }
          if (!modelo.match(/^[A-Za-z0-9]+$/)) {
              alert("El modelo debe ser alfanumérico.");
              return;
          }
          if(modelo.length>25){
            alert("El modelo debe tener 25 caracteres o menos.");
            return;
          }
          if (!precio) {
            alert("El precio es requerido.");
            return;
        }
          if (precio < 100) {
              alert("El precio debe ser mayor a 99.99.");
              return;
          }
          if (detalles.length > 250) {
              alert("Los detalles no pueden exceder los 250 caracteres.");
              return;
          }
          if (!unidades) {
            alert("Las unidades son requeridas.");
            return;
        }
          if (unidades<0) {
              alert("Las unidades deben ser 0 o más.");
              return;
          }
          if (!imagen) {
              imagenEl.value = "src/imagen.png";
          }
          
          alert("Formulario enviado con éxito!");
          this.submit();
      });
  </script>

</body>

</html>

<!-- document.getElementById("formulario").addEventListener("submit", function(event) {
            event.preventDefault();

            const DEFAULT_IMAGE = "src/imagen.png";

            const nombre = document.getElementById("id_name").value;
            const marca = document.getElementById("id_marca").value;
            const modelo = document.getElementById("id_modelo").value;
            const precioV = document.getElementById("id_precio").value;
            const precio = parseFloat(precioV);
            const detalles = document.getElementById("id_detalles").value;
            const unidadesV = document.getElementById("id_unidades").value;
            const unidades = parseInt(unidadesV, 10);
            const imagenEl = document.getElementById("id_imagen");
            let imagen = imagenEl.value.trim();

            //Nombre
            if (!nombre) {
                alert("El nombre es requerido");
                return;
            }
            if (nombre.length > 100) {
                alert("El nombre debe tener 100 caracteres o menos");
                return;
            }

            //Marca
            if (!marca) {
                alert("La marca es requerida.");
                return;
            }

            // c) Modelo
            if (!modelo) {
                alert("El modelo es requerido");
                return;
            }
            if (!/^[A-Za-z0-9]+$/.test(modelo)) {
                alert("El modelo debe ser alfanumérico");
                return;
            }
            if (modelo.length > 25) {
                alert("El modelo debe tener 25 caracteres o menos");
                return;
            }

            //Precio
            if (precioV === "" || isNaN(precio)) {
                alert("El precio es requerido");
                return;
            }
            if (precio <= 99.99) {
                alert("El precio debe ser mayor a 99.99");
                return;
            }

            // e) Detalles (opcional)
            if (detalles.length > 250) {
                alert("Los detalles deben ser menores a 250 caracteres");
                return;
            }

            //Unidades
            if (unidadesV === "" || isNaN(unidades)) {
                alert("Las unidades son requeridas");
                return;
            }
            if (unidades < 0) {
                alert("Las unidades deben ser 0 o más");
                return;
            }

            //Imagen
            if (!imagen) {
                imagenEl.value = DEFAULT_IMAGE;
            }

            alert("El formulario se envió con exito");
            this.submit();
        });
    </script>-->