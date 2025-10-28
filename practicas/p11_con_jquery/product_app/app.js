// ====== JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    document.getElementById("description").value = JsonString;
    cargarLista();   // i) lista completa al abrir
}

// ====== Helpers de UI (barra de estado)
function mostrarEstado(status, message) {
    $('#product-result').removeClass('d-none').addClass('d-block');
    $('#container').html(`
    <li style="list-style:none;">status: ${String(status).toLowerCase() === 'success' ? 'success' : 'error'}</li>
    <li style="list-style:none;">message: ${message || ''}</li>
  `);
}
function ocultarEstado() { $('#product-result').addClass('d-none').removeClass('d-block'); $('#container').empty(); }

// ====== Render de tabla (nombre clickeable para editar)
function renderTabla(productos) {
    if (!Array.isArray(productos) || productos.length === 0) {
        $('#products').empty(); return;
    }
    const filas = productos.map(p => `
    <tr productId="${p.id}">
      <td>${p.id}</td>
      <td><a href="#" class="product-item">${p.nombre}</a></td>
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
        // no ocultes la barra para que se vea el último success si lo hubo
    }).fail(function (xhr) {
        renderTabla([]);
        mostrarEstado('error', xhr?.responseText || 'No se pudo cargar la lista.');
    });
}

$(function () {
    let edit = false;

    // ii & iii) Búsqueda en vivo (tabla + barra de nombres)
    $('#search').on('input', function () {
        const q = $(this).val().trim();
        if (q.length === 0) { ocultarEstado(); cargarLista(); return; }

        $.getJSON('./backend/product-search.php', { search: q }, function (productos) {
            if (Array.isArray(productos) && productos.length > 0) {
                const barra = productos.map(p => `<li>${p.nombre}</li>`).join('');
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(barra);
            } else {
                ocultarEstado();
            }
            renderTabla(productos);
        }).fail(function (xhr) {
            mostrarEstado('error', xhr?.responseText || 'Error en búsqueda.');
        });
    });

    // iv & v) Agregar / Actualizar
    $('#product-form').on('submit', function (e) {
        e.preventDefault();

        let payload;
        try { payload = JSON.parse($('#description').val() || '{}'); }
        catch { mostrarEstado('error', 'JSON inválido en la descripción.'); return; }
        payload.nombre = ($('#name').val() || '').trim();

        // Validaciones mínimas
        if (!payload.nombre) return mostrarEstado('error', 'El nombre es obligatorio.');
        if (payload.unidades != null && (+payload.unidades) < 1) return mostrarEstado('error', 'Unidades debe ser ≥ 1.');
        if (payload.precio != null && (+payload.precio) < 0) return mostrarEstado('error', 'Precio debe ser ≥ 0.');

        const url = edit ? './backend/product-edit.php' : './backend/product-add.php';
        if (edit) payload.id = $('#productId').val();

        $.ajax({
            url,
            method: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json; charset=UTF-8',
            success: function (resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch { }
                mostrarEstado(resp?.status || 'success', resp?.message || (edit ? 'Producto actualizado' : 'Producto agregado'));
                cargarLista();                               // recargar tabla
                $('#product-form')[0].reset();               // limpiar form
                $('#description').val(JSON.stringify(baseJSON, null, 2));
                if (edit) { edit = false; $('#productId').val(''); $('#product-form button[type="submit"]').text('Agregar Producto'); }
            },
            error: function (xhr) {
                mostrarEstado('error', xhr?.responseText || 'No se pudo procesar la solicitud.');
            }
        });
    });

    // (cliente) Modo edición: click en nombre
    $('#products').on('click', '.product-item', function (e) {
        e.preventDefault();
        const id = $(this).closest('tr').attr('productId');

        $.post('./backend/product-single.php', { id }, function (resp) {
            let p; try { p = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch { }
            if (!p || !p.id) { mostrarEstado('error', 'Producto no encontrado.'); return; }

            // llena form
            $('#name').val(p.nombre);
            $('#productId').val(p.id);

            const current = tryParse($('#description').val()) || {};
            const merged = Object.assign({}, current, p); // mezcla para que textarea quede completo
            $('#description').val(JSON.stringify(merged, null, 2));

            edit = true;
            $('#product-form button[type="submit"]').text('Actualizar Producto');
            mostrarEstado('success', `Editando: ${p.nombre} (id ${p.id})`);
        }).fail(function (xhr) {
            mostrarEstado('error', xhr?.responseText || 'No se pudo cargar el producto.');
        });
    });

    // vi) Eliminar
    $('#products').on('click', '.product-delete', function () {
        const id = $(this).closest('tr').attr('productId');
        if (!confirm('De verdad deseas eliminar el Producto')) return;

        $.getJSON('./backend/product-delete.php', { id }, function (resp) {
            try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch { }
            mostrarEstado(resp?.status || 'success', resp?.message || 'Producto eliminado');
            cargarLista();
        }).fail(function (xhr) {
            mostrarEstado('error', xhr?.responseText || 'Error al eliminar.');
        });
    });

    function tryParse(s) { try { return JSON.parse(s); } catch { return null; } }
});
