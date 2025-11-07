// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

$(document).ready(function () {
    let edit = false;

    let JsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function (response) {
                // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                const productos = JSON.parse(response);

                // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                if (Object.keys(productos).length > 0) {
                    // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                    let template = '';

                    productos.forEach(producto => {
                        // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                        let descripcion = '';
                        descripcion += '<li>precio: ' + producto.precio + '</li>';
                        descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                        descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                        descripcion += '<li>marca: ' + producto.marca + '</li>';
                        descripcion += '<li>detalles: ' + producto.detalles + '</li>';

                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger" onclick="eliminarProducto()">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                    $('#products').html(template);
                }
            }
        });
    }

    $('#search').keyup(function () {
        if ($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php?search=' + $('#search').val(),
                data: { search },
                type: 'GET',
                success: function (response) {
                    if (!response.error) {
                        // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                        const productos = JSON.parse(response);

                        // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                        if (Object.keys(productos).length > 0) {
                            // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                            let template = '';
                            let template_bar = '';

                            productos.forEach(producto => {
                                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                                let descripcion = '';
                                descripcion += '<li>precio: ' + producto.precio + '</li>';
                                descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                                descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                                descripcion += '<li>marca: ' + producto.marca + '</li>';
                                descripcion += '<li>detalles: ' + producto.detalles + '</li>';

                                template += `
                                    <tr productId="${producto.id}">
                                        <td>${producto.id}</td>
                                        <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                        <td><ul>${descripcion}</ul></td>
                                        <td>
                                            <button class="product-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;

                                template_bar += `
                                    <li>${producto.nombre}</il>
                                `;
                            });
                            // SE HACE VISIBLE LA BARRA DE ESTADO
                            $('#product-result').show();
                            // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
                            $('#container').html(template_bar);
                            // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                            $('#products').html(template);
                        }
                    }
                }
            });
        }
        else {
            $('#product-result').hide();
        }
    });

    $('#product-form').submit(e => {
        e.preventDefault();

        // SE CONVIERTE EL JSON DE STRING A OBJETO
        //let postData = JSON.parse( $('#description').val() );
        // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
        // postData['nombre'] = $('#name').val();
        //postData['id'] = $('#productId').val();

        //////////////////////////////////////////////////
        // lo que haces aquí es que validamos con un alert la exitencia de un nombre en la bd antes de agregar
        if ($('#name').hasClass('is-invalid')) {
            const n = $('#name').val().trim();
            alert('El nombre "${n}" ya existe.');
            return;
        }
        ////////////////////////////////////////

        //textarea
        let postData = {
            nombre: $('#name').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            precio: $('#precio').val(),
            unidades: $('#unidades').val(),
            detalles: $('#detalles').val(),
            imagen: $('#imagen').val(),
            id: $('#productId').val()
        };

        /**
         * AQUÍ DEBES AGREGAR LAS VALIDACIONES DE LOS DATOS EN EL JSON
         * --> EN CASO DE NO HABER ERRORES, SE ENVIAR EL PRODUCTO A AGREGAR
         **/
        /////////////////////////////
        //Estas son mis validaciones de la practica 9 para antes de enviar el form
        let errores = [];

        if (postData.nombre.trim() === '' || postData.nombre.length > 100)
            errores.push('->El nombre es obligatorio y máximo 100 caracteres.');

        if (postData.marca.trim() === '')
            errores.push('->Selecciona una marca.');

        if (postData.modelo.trim() === '' || postData.modelo.length > 25 || !/^[a-zA-Z0-9\-]+$/.test(postData.modelo))
            errores.push('->Modelo requerido, alfanumérico y máximo 25 caracteres.');

        const precioNum = parseFloat(postData.precio);
        if (isNaN(precioNum) || precioNum <= 99.99)
            errores.push('->El precio debe ser mayor a $99.99.');

        const unidadesNum = parseInt(postData.unidades);
        if (isNaN(unidadesNum) || unidadesNum < 0)
            errores.push('->Las unidades deben ser 0 o más.');

        if ((postData.detalles || '').length > 250)
            errores.push('->Detalles menor a 250 caracteres.');

        if (errores.length > 0) {
            // barra de estado usando template_bar que nos proporciona arriba
            let template_bar = '<li style="list-style:none; font-weight:bold;">Error de envío:</li>';
            errores.forEach(err => {
                template_bar += `<li style="list-style:none;">${err}</li>`;
            });

            // Mostrar barra y mensajes
            $('#product-result').show();
            $('#container').html(template_bar);

            return; // detenemos el envío
        }

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';

        $.post(url, postData, (response) => {
            //console.log(response);
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let respuesta = JSON.parse(response);
            // SE CREA UNA PLANTILLA PARA CREAR INFORMACIÓN DE LA BARRA DE ESTADO
            let template_bar = '';
            template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
            // REINICIA EL FORMULARIO (versión con inputs)
            $('#name, #marca, #modelo, #precio, #unidades, #detalles, #imagen').val('');
            $('#productId').val('');

            // Muestra barra de estado 
            $('#product-result').show();
            $('#container').html(template_bar);

            // Vuelve a listar y salir de modo edición
            listarProductos();
            edit = false;


            /***********AGREGAMOS ESTA LÍNEA PARA REGRESAR AL TEXTO ORIGINAL DESPUÉS DE MODIFICAR UN PRODUCTO****** */
            $('button.btn-primary').text('Agregar Producto');

        });
    });

    $(document).on('click', '.product-delete', (e) => {
        if (confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', { id }, (response) => {
                $('#product-result').hide();
                listarProductos();
            });
        }
    });

    $(document).on('click', '.product-item', (e) => {
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('productId');
        $.post('./backend/product-single.php', { id }, (response) => {
            // SE CONVIERTE A OBJETO EL JSON OBTENIDO
            let product = JSON.parse(response);
            // SE INSERTAN LOS DATOS ESPECIALES EN LOS CAMPOS CORRESPONDIENTES
            $('#name').val(product.nombre);
            // EL ID SE INSERTA EN UN CAMPO OCULTO PARA USARLO DESPUÉS PARA LA ACTUALIZACIÓN
            $('#productId').val(product.id);
            // SE ELIMINA nombre, eliminado E id PARA PODER MOSTRAR EL JSON EN EL <textarea>
            /*delete (product.nombre);
            delete (product.eliminado);
            delete (product.id);
            // SE CONVIERTE EL OBJETO JSON EN STRING
            let JsonString = JSON.stringify(product, null, 2);
            // SE MUESTRA STRING EN EL <textarea>
            $('#description').val(JsonString);*/

            //textarea
            $('#marca').val(product.marca || '');
            $('#modelo').val(product.modelo || '');
            $('#precio').val(product.precio || '');
            $('#unidades').val(product.unidades || '');
            $('#detalles').val(product.detalles || '');
            $('#imagen').val(product.imagen || '');


            // SE PONE LA BANDERA DE EDICIÓN EN true
            edit = true;

            /***********AGREGAMOS LÍNEA PARA MODIFICAR EL TEXTO DEL BOTON A  MODIFICAR PRODUCTO******** */
            $('button.btn-primary').text("Modificar Producto");

        });
        e.preventDefault();
    });

    ///////////////////////////////////////
    function setError($input, msg) {
        $input.addClass('is-invalid');
        $input.siblings('.invalid-feedback').text(msg).show();
    }
    function clearError($input) {
        $input.removeClass('is-invalid');
        $input.siblings('.invalid-feedback').text('').hide();
    }

    //validamos pero sin alert, en este caso usamos 
    // blur para que el texto aparezca debajo del input cuando salimos de este mismo
    $('#name').on('blur input', function () {
        const $el = $(this);
        const v = $el.val().trim();
        if (v === '' || v.length > 100) setError($el, 'El nombre es obligatorio y de máximo 100 caracteres.');
        else clearError($el);
    });

    $('#marca').on('blur change', function () {
        const $el = $(this);
        if ($el.val().trim() === '') setError($el, 'Selecciona una marca.');
        else clearError($el);
    });

    $('#modelo').on('blur input', function () {
        const $el = $(this);
        const v = $el.val().trim();
        if (v === '' || v.length > 25 || !/^[a-zA-Z0-9\-]+$/.test(v))
            setError($el, 'Modelo requerido, alfanumérico, máximo 25.');
        else clearError($el);
    });

    $('#precio').on('blur input', function () {
        const $el = $(this);
        const v = parseFloat($el.val());
        if (isNaN(v) || v <= 99.99) setError($el, 'El precio debe ser mayor a $99.99.');
        else clearError($el);
    });

    $('#unidades').on('blur input', function () {
        const $el = $(this);
        const v = parseInt($el.val());
        if (isNaN(v) || v < 0) setError($el, 'Las unidades deben ser 0 o más.');
        else clearError($el);
    });

    $('#detalles').on('blur input', function () {
        const $el = $(this);
        const v = $el.val();
        if (v.length > 250) setError($el, 'Máximo 250 caracteres.');
        else clearError($el);
    });



    /////////////////////////////////////
    //Aqui es donde hacemos nuestra validación de existencia de un producto que queramos agregar
    let timerNombre = null;

    $('#name').on('keyup blur', function () {
        const nombre = $(this).val().trim();

        // Limpiamos el estado si es que está vacío
        if (nombre.length === 0) {
            $('#name').removeClass('is-invalid');
            return;
        }

        clearTimeout(timerNombre);
        timerNombre = setTimeout(function () {
            // Usa el buscador que ya exite product-search.php?search=
            $.ajax({
                url: './backend/product-search.php',
                type: 'GET',
                data: { search: nombre }, // el endpoint ya acepta ?search=
                success: function (resp) {
                    let arr = [];
                    try { arr = JSON.parse(resp) || []; } catch (e) { }

                    // buscamos si hay un nombre igual en la bd
                    const existe = arr.some(p => (p.nombre || '').toLowerCase() === nombre.toLowerCase());

                    if (existe) {
                        // Marca el input
                        $('#name').addClass('is-invalid');

                        // usamos la barra de estado para avisar que ya existe ese producto
                        const template_bar = `
            <li style="list-style:none;font-weight:bold;">El nombre "${nombre}" ya existe.</li>
          `;
                        $('#product-result').show();      // card ya existe
                        $('#container').html(template_bar); // ul ya existe
                    } else {
                        $('#name').removeClass('is-invalid');
                        // No oculto la barra aquí para no interferir con otros mensajes
                    }
                }
            });
        }, 300); // debounce
    });

});