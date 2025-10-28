// ====== JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
  "precio": 0.0,
  "unidades": 1,
  "modelo": "XX-000",
  "marca": "NA",
  "detalles": "NA",
  "imagen": "img/default.png"
};

// ====== Cargar JSON base y la lista al iniciar
function init() {
  var JsonString = JSON.stringify(baseJSON, null, 2);
  document.getElementById("description").value = JsonString;
  // i) Cargar toda la lista de NO eliminados al abrir
  cargarLista();
}

// ====== Helpers de UI (barra de estado)
function mostrarEstado(status, message) {
  $('#product-result').removeClass('d-none').addClass('d-block');
  $('#container').html(`
    <li style="list-style:none;">status: ${String(status).toLowerCase()==='success'?'success':'error'}</li>
    <li style="list-style:none;">message: ${message || ''}</li>
  `);
}
function ocultarEstado() {
  $('#product-result').addClass('d-none').removeClass('d-block');
  $('#container').empty();
}

// ====== Renderizador de tabla
function renderTabla(productos) {
  if (!Array.isArray(productos) || productos.length === 0) {
    $('#products').empty();
    return;
  }
  const filas = productos.map(p => `
    <tr productId="${p.id}">
      <td>${p.id}</td>
      <td>${p.nombre}</td>
      <td>
        <ul>
          <li>precio: ${p.precio}</li>
          <li>unidades: ${p.unidades}</li>
          <li>modelo: ${p.modelo}</li>
          <li>marca: ${p.marca}</li>
          <li>detalles: ${p.detalles}</li>
        </ul>
      </td>
      <td><button class="product-delete btn btn-danger">Eliminar</button></td>
    </tr>`).join('');
  $('#products').html(filas);
}

// ====== i) Listar productos al abrir
function cargarLista() {
  $.getJSON('./backend/product-list.php', function (productos) {
    renderTabla(productos);   
    // ocultarEstado();
  }).fail(function (xhr) {
    renderTabla([]);          
    mostrarEstado('error', xhr?.responseText || 'No se pudo cargar la lista.');
  });
}


// ====== Al cargar el DOM montamos todos los bindings
$(function () {

  // ii) & iii) Búsqueda “en vivo” (tabla + barra de estado)
  $('#search').on('input', function () {
    const q = $(this).val().trim();

    if (q.length === 0) {     // si limpian el campo, vuelve a la lista completa
      ocultarEstado();
      cargarLista();
      return;
    }

    $.getJSON('./backend/product-search.php', { search: q }, function (productos) {
      // iii) barra de estado con SOLO nombres coincidentes
      if (Array.isArray(productos) && productos.length > 0) {
        const barra = productos.map(p => `<li>${p.nombre}</li>`).join('');
        $('#product-result').removeClass('d-none').addClass('d-block');
        $('#container').html(barra);
      } else {
        ocultarEstado();
      }
      // ii) tabla con los coincidentes
      renderTabla(productos);
    }).fail(function (xhr) {
      mostrarEstado('error', xhr?.responseText || 'Error en búsqueda.');
    });
  });

  // iv) & v) Agregar producto (POST JSON) + recargar lista
  $('#product-form').on('submit', function (e) {
    e.preventDefault();

    let base;
    try {
      base = JSON.parse($('#description').val() || '{}');
    } catch (err) {
      mostrarEstado('error', 'JSON inválido en la descripción.');
      return;
    }
    base.nombre = ($('#name').val() || '').trim();

    // Validaciones mínimas
    if (!base.nombre) return mostrarEstado('error', 'El nombre es obligatorio.');
    if (base.unidades != null && (+base.unidades) < 1) return mostrarEstado('error', 'Unidades debe ser ≥ 1.');
    if (base.precio != null && (+base.precio) < 0) return mostrarEstado('error', 'Precio debe ser ≥ 0.');

    $.ajax({
      url: './backend/product-add.php',
      method: 'POST',
      data: JSON.stringify(base),
      contentType: 'application/json; charset=UTF-8',
      success: function (resp) {
        // resp esperado: {status, message}
        try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch {}
        mostrarEstado(resp?.status || 'success', resp?.message || 'Producto agregado.');
        // v) recargar lista inmediatamente
        cargarLista();
        // Reset form y JSON base
        $('#product-form')[0].reset();
        $('#description').val(JSON.stringify(baseJSON, null, 2));
      },
      error: function (xhr) {
        // iv) también mostrar mensaje en caso de error
        mostrarEstado('error', xhr?.responseText || 'No se pudo registrar el producto.');
      }
    });
  });

  // vi) Eliminar producto (delegación) + recargar lista
  $('#products').on('click', '.product-delete', function () {
    const id = $(this).closest('tr').attr('productId');
    if (!confirm('De verdad deseas eliminar el Producto')) return;

    $.getJSON('./backend/product-delete.php', { id }, function (resp) {
      try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch {}
      mostrarEstado(resp?.status || 'success', resp?.message || 'Producto eliminado.');
      // vi) recargar lista inmediatamente
      cargarLista();
    }).fail(function (xhr) {
      mostrarEstado('error', xhr?.responseText || 'Error al eliminar.');
    });
  });

});
