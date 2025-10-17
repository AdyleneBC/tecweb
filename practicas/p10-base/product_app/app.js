// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

// FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarID(e) {
    /**
     * Revisar la siguiente información para entender porqué usar event.preventDefault();
     * http://qbit.com.mx/blog/2013/01/07/la-diferencia-entre-return-false-preventdefault-y-stoppropagation-en-jquery/#:~:text=PreventDefault()%20se%20utiliza%20para,escuche%20a%20trav%C3%A9s%20del%20DOM
     * https://www.geeksforgeeks.org/when-to-use-preventdefault-vs-return-false-in-javascript/
     */
    e.preventDefault();

    // SE OBTIENE EL ID A BUSCAR
    var id = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);    // similar a eval('('+client.responseText+')');
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>nombre: '+productos[0].nombre+'</li>';
                    descripcion += '<li>precio: '+productos[0].precio+'</li>';
                    descripcion += '<li>unidades: '+productos[0].unidades+'</li>';
                    descripcion += '<li>modelo: '+productos[0].modelo+'</li>';
                    descripcion += '<li>marca: '+productos[0].marca+'</li>';
                    descripcion += '<li>detalles: '+productos[0].detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos[0].id}</td>
                            <td>${productos[0].nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

//FUNCIÓN BUSCAR PRODUCTO
function buscarProducto(e, inputId, parametro) {
    e.preventDefault();

    // SE OBTIENE EL ID A BUSCAR
    let nombre = document.getElementById(inputId).value.trim();
    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);    // similar a eval('('+client.responseText+')');
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            //if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                if (productos.length > 0) {
                    let template = "";
    
                    productos.forEach(producto => {
                        let descripcion = `
                            <ul>
                                <li>Precio: ${producto.precio}</li>
                                <li>Unidades: ${producto.unidades}</li>
                                <li>Modelo: ${producto.modelo}</li>
                                <li>Marca: ${producto.marca}</li>
                                <li>Detalles: ${producto.detalles}</li>
                            </ul>`;
    
                        template += `
                            <tr>
                                <td>${producto.id}</td>
                                <td>${producto.nombre}</td>
                                <td>${descripcion}</td>
                            </tr>`;
                    });
    
                
                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }else {
                document.getElementById("productos").innerHTML = "<tr><td colspan='3'>No se encontraron productos</td></tr>";
            }
        }
    };
    client.send(new URLSearchParams({ [parametro]: nombre }).toString());
}

//MENSAJE DE ALERTA
function mostrarMensaje(mensaje) {
    console.log("Mensaje para alerta:", mensaje);  // Ver en consola el mensaje
    window.alert(mensaje);  // Mostrar en el alert
}

function validarProducto(finalJSON, nombre) {
    let errores = [];

    if (!nombre || nombre.length > 100) {
        errores.push("El nombre es obligatorio y debe tener 100 caracteres o menos.");
    }

    let marca = finalJSON.marca ? finalJSON.marca.trim() : "";
    if (!marca||marca=="NA") {
        errores.push("La marca es obligatoria.");
    }

    let modelo = finalJSON.modelo ? finalJSON.modelo.trim() : "";
    let modeloRegex = /^[a-zA-Z0-9\-]+$/; // Solo letras, números y guiones
    if (!modelo || modelo.length > 25 || !modeloRegex.test(modelo)) {
        errores.push("El modelo es obligatorio, alfanumérico y debe tener 25 caracteres o menos.");
    }

    let precio = parseFloat(finalJSON.precio);
    if (isNaN(precio) || precio <= 99.99) {
        errores.push("El precio es obligatorio y debe ser mayor a 99.99.");
    }

    let detalles = finalJSON.detalles ? finalJSON.detalles.trim() : "";
    if (detalles.length > 250) {
        errores.push("Los detalles deben tener 250 caracteres o menos.");
    }

    let unidades = parseInt(finalJSON.unidades);
    if (isNaN(unidades) || unidades <= 0) {
        errores.push("Las unidades son obligatorias y deben ser 0 o más.");
    }

    return errores;
}
// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // SE OBTIENE DESDE EL FORMULARIO EL JSON A ENVIAR
    var productoJsonString = document.getElementById('description').value;
    // SE CONVIERTE EL JSON DE STRING A OBJETO
    var finalJSON = JSON.parse(productoJsonString);
    // SE OBTIENE EL NOMBRE DESDE EL FORMULARIO
    let nombre = document.getElementById('name').value.trim();

    // LLAMAR A LA FUNCIÓN DE VALIDACIÓN
    let errores = validarProducto(finalJSON, nombre);

    // SI HAY ERRORES, SE MUESTRAN Y SE DETIENE LA EJECUCIÓN
    if (errores.length > 0) {
        mostrarMensaje("Errores:\n" + errores.join("\n"));
        return;
    }

    // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
    finalJSON['nombre'] = nombre;
    // SE OBTIENE EL STRING DEL JSON FINAL
    productoJsonString = JSON.stringify(finalJSON,null,2);

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/create.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
           let response = JSON.parse(client.responseText);
            if (response.success) {
                console.log("Success: " + response.success);
                mostrarMensaje(response.success);
            } else if (response.error) {
                console.log("Error: " + response.error);
                mostrarMensaje(response.error);
            }
        }
    };
    client.send(productoJsonString);
}

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;

    try{
        objetoAjax = new XMLHttpRequest();
    }catch(err1){
        /**
         * NOTA: Las siguientes formas de crear el objeto ya son obsoletas
         *       pero se comparten por motivos historico-académicos.
         */
        try{
            // IE7 y IE8
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                // IE5 y IE6
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}