// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// ruta base del backend
const API = "./backend";

$(document).ready(function () {
    let edit = false;

    let JsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    //
    function safeParse(resp) {
        if (typeof resp === "string") {
            try { return JSON.parse(resp); }
            catch (e) {
                console.log("Respuesta no JSON:", resp);
                return { status: "error", message: "Respuesta no válida del servidor" };
            }
        }
        return resp;
    }

    function listarProductos() {
        $.ajax({
            url: API + '/products', //
            type: 'GET',
            success: function (response) {
                const productos = JSON.parse(response);

                if (Object.keys(productos).length > 0) {
                    let template = '';

                    productos.forEach(producto => {
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
                                  <button class="product-delete btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#products').html(template);
                }
            }
        });
    }

    $('#search').keyup(function () {
        if ($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: API + '/products/' + $('#search').val(), //
                type: 'GET',
                success: function (response) {
                    if (!response.error) {
                        const productos = JSON.parse(response);

                        if (Object.keys(productos).length > 0) {
                            let template = '';
                            let template_bar = '';

                            productos.forEach(producto => {
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

                            $('#product-result').show();
                            $('#container').html(template_bar);
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

        if ($('#name').hasClass('is-invalid')) {
            const n = $('#name').val().trim();
            alert('El nombre "${n}" ya existe.');
            return;
        }

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

        let errores = [];

        if (postData.nombre.trim() === '' || postData.nombre.length > 100)
            errores.push('->El nombre es obligatorio');

        if (postData.marca.trim() === '')
            errores.push('->Selecciona una marca.');

        if (postData.modelo.trim() === '')
            errores.push('->Modelo requerido');

        const precioNum = parseFloat(postData.precio);
        if (isNaN(precioNum))
            errores.push('->Precio requerido');

        const unidadesNum = parseInt(postData.unidades);
        if (isNaN(unidadesNum))
            errores.push('->Unidades requeridas');

        if ((postData.detalles || '').length > 250)
            errores.push('->Detalles menor a 250 caracteres.');

        if (errores.length > 0) {
            let template_bar = '<li style="list-style:none; font-weight:bold;">Error de envío:</li>';
            errores.forEach(err => {
                template_bar += `<li style="list-style:none;">${err}</li>`;
            });

            $('#product-result').show();
            $('#container').html(template_bar);

            return;
        }

        const url = API + '/product'; 

        if (edit === false) {
            // POST /product
            $.post(url, postData, (response) => {
                let respuesta = safeParse(response); 

                let template_bar = '';
                template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;

                $('#name, #marca, #modelo, #precio, #unidades, #detalles, #imagen').val('');
                $('#productId').val('');

                $('#product-result').show();
                $('#container').html(template_bar);

                listarProductos();
                edit = false;
                $('button.btn-primary').text('Agregar Producto');
            });

        } else {
            // PUT /product
            $.ajax({
                url: url,
                type: 'PUT',
                data: postData,
                success: function (response) {
                    let respuesta = safeParse(response); 

                    let template_bar = '';
                    template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;

                    $('#name, #marca, #modelo, #precio, #unidades, #detalles, #imagen').val('');
                    $('#productId').val('');

                    $('#product-result').show();
                    $('#container').html(template_bar);

                    listarProductos();
                    edit = false;
                    $('button.btn-primary').text('Agregar Producto');
                }
            });
        }
    });

    $(document).on('click', '.product-delete', (e) => {
        if (confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');

            // DELETE /product
            $.ajax({
                url: API + '/product',
                type: 'DELETE',
                data: { id },
                success: function (response) {
                    $('#product-result').hide();
                    listarProductos();
                }
            });
        }
    });

    // MODIFICADO PARA AUTO LLENAR CAMPOS AL MODIFICAR
    $(document).on('click', '.product-item', (e) => {
        $(document).on('click', '.product-item', function (e) {
            e.preventDefault();

            const $row = $(e.target).closest('tr');
            const id = $row.attr('productId');

            // GET /product/{id}
            $.ajax({
                url: API + '/product/' + id, 
                type: 'GET',
                success: function (response) {
                    let data;
                    try { data = JSON.parse(response); } catch (err) { data = {}; }

                    const product = Array.isArray(data) ? (data[0] || {}) : data;

                    $('#name').val(product.nombre || '');
                    $('#productId').val(product.id || '');
                    $('#marca').val(product.marca || '');
                    $('#modelo').val(product.modelo || '');
                    $('#precio').val(product.precio || '');
                    $('#unidades').val(product.unidades || '');
                    $('#detalles').val(product.detalles || '');
                    $('#imagen').val(product.imagen || '');

                    edit = true;
                    $('button.btn-primary').text('Modificar Producto');
                }
            });
        });
    });

    function setError($input, msg) {
        $input.addClass('is-invalid');
        $input.siblings('.invalid-feedback').text(msg).show();
    }
    function clearError($input) {
        $input.removeClass('is-invalid');
        $input.siblings('.invalid-feedback').text('').hide();
    }

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

    // validación de existencia de nombre
    let timerNombre = null;

    $('#name').on('keyup blur', function () {
        const nombre = $(this).val().trim();

        if (nombre.length === 0) {
            $('#name').removeClass('is-invalid');
            return;
        }

        clearTimeout(timerNombre);
        timerNombre = setTimeout(function () {
            $.ajax({
                url: API + '/products/' + nombre, 
                type: 'GET',
                success: function (resp) {
                    let arr = [];
                    try { arr = JSON.parse(resp) || []; } catch (e) { }

                    const existe = arr.some(p => (p.nombre || '').toLowerCase() === nombre.toLowerCase());

                    if (existe) {
                        $('#name').addClass('is-invalid');

                        const template_bar = `
            <li style="list-style:none;font-weight:bold;">El nombre "${nombre}" ya existe :(</li>
          `;
                        $('#product-result').show();
                        $('#container').html(template_bar);
                    } else {
                        const template_bar = `
            <li style="list-style:none;font-weight:bold;">El nombre "${nombre}" sin coincidencias :)</li>
          `;
                        $('#product-result').show();
                        $('#container').html(template_bar);
                        $('#name').removeClass('is-invalid');
                    }
                }
            });
        }, 300);
    });

});
