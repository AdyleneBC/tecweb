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
            let productos = JSON.parse(client.responseText);    
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>precio: '+productos.precio+'</li>';
                    descripcion += '<li>unidades: '+productos.unidades+'</li>';
                    descripcion += '<li>modelo: '+productos.modelo+'</li>';
                    descripcion += '<li>marca: '+productos.marca+'</li>';
                    descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos.id}</td>
                            <td>${productos.nombre}</td>
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

function buscarProducto(e) {
    e.preventDefault();

    // SE OBTIENE LA PALABRA CLAVE A BUSCAR
    var keyword = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n' + client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);

            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(productos.length > 0) {
                let template = '';
                productos.forEach(producto => {
                    let descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                    `;

                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;
                });

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            } else {
                document.getElementById("productos").innerHTML = '<tr><td colspan="3">No se encontraron productos.</td></tr>';
            }
        }
    };
    client.send("id=" + keyword);
}

function validarProducto(finalJSON) {
  const errores = [];

  const nombre = String(finalJSON.nombre || "").trim();
  const nombreRegex = /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\s\-.,#()]+$/; // letras (con acentos), números, espacio y - . , # ( )

  if (!nombre || nombre.length > 100 || !nombreRegex.test(nombre)) {
    errores.push("El nombre es obligatorio, <=100 caracteres y sin símbolos raros.");
  }

  const marca = (finalJSON.marca || "").trim();
  if (!marca || marca === "NA") errores.push("La marca es obligatoria.");

  const modelo = (finalJSON.modelo || "").trim();
  const modeloRegex = /^[A-Za-z0-9\-]+$/;
  if (!modelo || modelo.length > 25 || !modeloRegex.test(modelo))
    errores.push("El modelo es obligatorio, alfanumérico/guion, <=25.");

  const precio = parseFloat(finalJSON.precio);
  if (isNaN(precio) || precio <= 99.99) errores.push("El precio debe ser > 99.99.");

  const detalles = (finalJSON.detalles || "").trim();
  if (detalles.length > 250) errores.push("Los detalles deben ser <=250 caracteres.");

  const unidades = parseInt(finalJSON.unidades, 10);
  if (isNaN(unidades) || unidades < 0) errores.push("Las unidades deben ser 0 o más.");

  return errores;
}


//FUNCIÓN CALLBACK DE BOTÓN "Agregar producto"
function agregarProducto(e) {
  e.preventDefault();

  const inputNombre = (document.getElementById("name").value || "").trim();
  let raw = document.getElementById("description").value || "";

  let finalJSON;
  try {
    finalJSON = JSON.parse(raw);
  } catch {
    alert("El contenido de la descripción no es un JSON válido.");
    return;
  }

  // si el input está vacío, usamos el nombre que venga en el JSON
  const nombre = inputNombre || String(finalJSON.nombre || "").trim();
  finalJSON.nombre   = nombre;                                  
  finalJSON.marca    = (finalJSON.marca    || "").trim();
  finalJSON.modelo   = (finalJSON.modelo   || "").trim();
  finalJSON.detalles = (finalJSON.detalles || "").trim();
  finalJSON.imagen   = (finalJSON.imagen   || "img/default.png").trim();
  finalJSON.precio   = Number(finalJSON.precio);
  finalJSON.unidades = Number.isInteger(finalJSON.unidades)
                        ? finalJSON.unidades
                        : parseInt(finalJSON.unidades || "0", 10);

  const errores = validarProducto(finalJSON);
  if (errores.length) {
    alert("Errores:\n" + errores.join("\n"));
    return;
  }

  const client = getXMLHttpRequest();
  client.open("POST", "./backend/create.php", true);
  client.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  client.onreadystatechange = function () {
    if (client.readyState === 4) {
      let res = {};
      try { res = JSON.parse(client.responseText || "{}"); } catch {}
      if (client.status === 200 && res.success) {
        alert(res.success + (res.insert_id ? " (id: " + res.insert_id + ")" : ""));
        document.getElementById("search").value = nombre;       
        buscarProducto(new Event("submit"));
      } else {
        alert(res.error || "Error desconocido al insertar.");
      }
    }
  };
  client.send(JSON.stringify(finalJSON));
}

