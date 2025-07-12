$(document).ready(function () {

    // =========================
    // 1. Funciones de productos y categorías (catálogo)
    // =========================

    mostrarProductos();
    mostrarCategorias();
    cargarCategoriasProducto();
    mostrarCategoriasLista();

    // Filtro de productos por nombre y categoría (catálogo cliente)
    $("#filtrosForm").submit(function (e) {
        e.preventDefault();
        const nombre = $("#buscarProducto").val();
        const categoria = $("#categoriaFiltro").val();
        mostrarProductos(nombre, categoria);
    });

    // Mostrar productos en el catálogo
    function mostrarProductos(nombre = "", categoria = "", pagina = 1, por_pagina = 6) {
        $.post("Modelo/MostrarProductos.php", {
            nombre: nombre,
            categoria: categoria,
            pagina: pagina,
            por_pagina: por_pagina
        }, function (respuesta) {
            $("#resultadoProductos").html(respuesta);
        });
    }

    // Mostrar categorías en el filtro
    function mostrarCategorias() {
        $.post("Modelo/MostrarCategorias.php", function (respuesta) {
            $("#categoriaFiltro").html(respuesta);
        });
    }

    // Cargar categorías en el formulario de productos
    function cargarCategoriasProducto() {
        $.post("Modelo/MostrarCategorias.php", function (respuesta) {
            $("#categoriaProducto").html('<option value="">Seleccionar categoría</option>' + respuesta.replace('<option value="">Todas las categorías</option>', ''));
        });
    }

    // Mostrar lista de categorías con botones de editar/eliminar
    function mostrarCategoriasLista() {
        $.post("Modelo/MostrarCategorias.php", function (respuesta) {
            let filas = "";
            $(respuesta).filter("option").each(function () {
                const id = $(this).val();
                const nombre = $(this).text();
                if (id !== "") {
                    filas += `<tr>
                        <td>${nombre}</td>
                        <td>
                            <button class="btn-editar-categoria" data-id="${id}" data-nombre="${nombre}" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-eliminar-categoria" data-id="${id}" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`;
                }
            });
            $("#listaCategorias").html(filas);
        });
    }

    // Cargar categorías en el formulario de edición
    function cargarCategoriasEdicion() {
        $.post("Modelo/MostrarCategorias.php", function (respuesta) {
            $("#editarCategoria").html('<option value="">Seleccionar categoría</option>' + respuesta.replace('<option value="">Todas las categorías</option>', ''));
        });
    }

    // =========================
    // 2. CRUD de productos y categorías (admin)
    // =========================

    // Abrir formulario de edición de producto
    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('producto');
        $.post('Modelo/obtenerProducto.php', { id: id }, function (data) {
            const producto = JSON.parse(data);
            $("#editarId").val(producto.id);
            $("#editarMarca").val(producto.marca);
            $("#editarModelo").val(producto.modelo);
            $("#editarTipo").val(producto.tipo);
            $("#editarEspecificaciones").val(producto.especificaciones);
            $("#editarPrecio").val(producto.precio);
            $("#editarCategoria").val(producto.id_categoria);
            $("#formEditarProductoOverlay").fadeIn();
        });
        cargarCategoriasEdicion();
    });

    // Cerrar formulario de edición de producto
    $("#cerrarFormEditarProducto").click(function () {
        $("#formEditarProductoOverlay").fadeOut();
    });

    // Enviar edición de producto
    $("#formEditarProducto").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "index.php?accion=editarProducto",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (resp) {
                if (resp.trim() === "ok") {
                    Swal.fire({
                        title: "¡Editado!",
                        text: "Producto editado correctamente",
                        icon: "success"
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Error al editar el producto",
                        icon: "error"
                    });
                }
            }
        });
    });

    // Eliminar producto con confirmación y alerta de dependencias
    $(document).on('click', '.btn-eliminar', function () {
        const id = $(this).data('producto');
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("index.php?accion=eliminarProducto", { id: id }, function (resp) {
                    if (resp.trim() === "ok") {
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "Producto eliminado correctamente",
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    } else if (resp.trim() === "tiene_pedidos") {
                        Swal.fire({
                            icon: "error",
                            title: "No se puede eliminar",
                            text: "Este producto no se puede borrar porque tiene pedidos asociados. Elimina primero los pedidos relacionados."
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Error al eliminar el producto",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });

    // Abrir formulario de edición de categoría
    $(document).on('click', '.btn-editar-categoria', function () {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        $("#editarCategoriaId").val(id);
        $("#editarCategoriaNombre").val(nombre);
        $("#formEditarCategoriaOverlay").fadeIn();
    });

    // Cerrar formulario de edición de categoría
    $("#cerrarFormEditarCategoria").click(function () {
        $("#formEditarCategoriaOverlay").fadeOut();
    });

    // Enviar edición de categoría
    $("#formEditarCategoria").submit(function (e) {
        e.preventDefault();
        const id = $("#editarCategoriaId").val();
        const nombre = $("#editarCategoriaNombre").val();
        $.ajax({
            url: "index.php?accion=editarCategoria",
            type: "POST",
            data: { id: id, nombre: nombre },
            success: function (respuesta) {
                if (respuesta.trim() === "ok") {
                    mostrarCategoriasLista();
                    $("#formEditarCategoriaOverlay").hide();
                    Swal.fire({
                        title: "¡Editado!",
                        text: "Categoría editada correctamente",
                        icon: "success"
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Error al editar la categoría",
                        icon: "error"
                    });
                }
            }
        });
    });

    // Eliminar categoría con confirmación y alerta de dependencias
    $(document).on('click', '.btn-eliminar-categoria', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Seguro que deseas eliminar esta categoría?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("index.php?accion=eliminarCategoria", { id: id }, function (resp) {
                    if (resp.trim() === "ok") {
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "Categoría eliminada correctamente",
                            icon: "success"
                        });
                        mostrarCategorias();
                        cargarCategoriasProducto();
                        mostrarCategoriasLista();
                    } else if (resp.trim() === "tiene_productos") {
                        Swal.fire({
                            icon: "error",
                            title: "No se puede eliminar",
                            text: "Esta categoría no se puede borrar porque tiene productos asociados. Elimina primero los productos de esta categoría."
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "No se pudo eliminar la categoría.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });

    // =========================
    // 3. Formularios de registro y login
    // =========================

    // Registro de cliente
    $("#formRegistroCliente").submit(function (e) {
        e.preventDefault();
        $.post("index.php?accion=registrarCliente", $(this).serialize(), function (resp) {
            if (resp.trim() === "ok") {
                Swal.fire({
                    title: "¡Registro exitoso!",
                    text: "Usuario registrado correctamente.",
                    icon: "success"
                }).then(() => {
                    window.location = "index.php?accion=iniciarCliente";
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo registrar el usuario. Intenta con otro correo.",
                    icon: "error"
                });
            }
        });
    });

    // Login cliente
    $("#formInicioCliente").submit(function (e) {
        e.preventDefault();
        $.post("index.php?accion=loginCliente", $(this).serialize(), function (resp) {
            if (resp.trim() === "ok") {
                Swal.fire({
                    title: "¡Bienvenido!",
                    text: "Inicio de sesión exitoso.",
                    icon: "success"
                }).then(() => {
                    window.location = "index.php?accion=vista";
                });
            } else {
                Swal.fire({
                    title: "Credenciales incorrectas",
                    text: "Correo o contraseña incorrectos.",
                    icon: "error"
                });
            }
        });
    });

    // Login administrador
    $("#formInicioAdmin").submit(function (e) {
        e.preventDefault();
        $.post("index.php?accion=loginAdmin", $(this).serialize(), function (resp) {
            if (resp.trim() === "ok") {
                Swal.fire({
                    title: "Bienvenido",
                    text: "Inicio de sesión exitoso.",
                    icon: "success"
                }).then(() => {
                    window.location = "index.php?accion=Administradores";
                });
            } else {
                Swal.fire({
                    title: "Credenciales incorrectas",
                    text: "Usuario o contraseña incorrectos.",
                    icon: "error"
                });
            }
        });
    });

    // =========================
    // 4. Formularios de productos y categorías (admin)
    // =========================

    // Guardar producto nuevo
    $("#formRegistrarProducto").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "index.php?accion=addProducto",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.trim() === "ok") {
                    Swal.fire({
                        title: "¡Producto guardado!",
                        text: "El producto se guardó correctamente.",
                        icon: "success"
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo guardar el producto.",
                        icon: "error"
                    });
                }
            }
        });
    });

    // Guardar categoría nueva
    $("#formCategoria").submit(function(e) {
        e.preventDefault();
        $.post("index.php?accion=addCategoria", $(this).serialize(), function(resp) {
            if (resp.trim() === "ok") {
                Swal.fire({
                    title: "¡Guardado!",
                    text: "Categoría guardada correctamente.",
                    icon: "success"
                });
                mostrarCategoriasLista();
                $("#nombreCategoria").val("");
            } else {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo guardar la categoría.",
                    icon: "error"
                });
            }
        });
    });

    // =========================
    // 5. Filtros avanzados (admin)
    // =========================

    // Cargar categorías en el filtro de productos admin
    function cargarCategoriasFiltroAdmin() {
        $.post("Modelo/MostrarCategorias.php", function (respuesta) {
            $("#filtroCategoriaFiltro").html(respuesta);
        });
    }
    cargarCategoriasFiltroAdmin();

    // Filtro productos admin
    $("#filtroProductosAdminFiltro").submit(function(e){
        e.preventDefault();
        $.post("Modelo/listarProductos.php", {
            marca: $("#filtroMarcaFiltro").val(),
            tipo: $("#filtroTipoFiltro").val(),
            categoria: $("#filtroCategoriaFiltro").val()
        }, function(resp){
            $("#tbodyProductosAdmin").html(resp);
        });
    });

    // Filtro pedidos admin
    $("#filtroPedidosAdminFiltro").submit(function(e){
        e.preventDefault();
        $.post("Modelo/listarPedidos.php", {
            id: $("#filtroIdPedidoFiltro").val(),
            cliente: $("#filtroClienteFiltro").val(),
            fecha: $("#filtroFechaFiltro").val(),
            estado: $("#filtroEstadoFiltro").val()
        }, function(resp){
            $("#tbodyPedidosAdmin").html(resp);
        });
    });

    // =========================
    // 6. Pedidos del cliente (mis pedidos)
    // =========================

    function cargarPedidosCliente() {
        const producto = $("#filtroProductoPedido").val();
        const estado = $("#filtroEstadoPedido").val();
        $.get('Modelo/listarPedidosCliente.php', { producto: producto, estado: estado }, function(resp) {
            $("#tbodyMisPedidos").html(resp);
        });
    }

    // Cargar todos los pedidos al inicio y al filtrar
    cargarPedidosCliente();
    $("#filtroPedidosCliente").submit(function(e) {
        e.preventDefault();
        cargarPedidosCliente();
    });

    // =========================
    // 7. Carrito de compras por usuario
    // =========================

    // Clave del carrito para el usuario actual
    function getCarritoKey() {
        return window.clienteCorreo ? 'carrito_cliente_' + window.clienteCorreo : 'carrito_cliente_guest';
    }

    // Obtener y guardar carrito
    function getCarrito() {
        return JSON.parse(localStorage.getItem(getCarritoKey()) || '[]');
    }
    function setCarrito(carrito) {
        localStorage.setItem(getCarritoKey(), JSON.stringify(carrito));
    }
    function limpiarCarrito() {
        localStorage.removeItem(getCarritoKey());
    }

    // Actualizar contador de carrito en navbar
    function actualizarCantidadCarrito() {
        const carrito = getCarrito();
        $("#carritoCantidad").text(carrito.length > 0 ? carrito.length : "");
    }

    // Mostrar modal del carrito
    $(document).on('click', '#btnVerCarrito', function (e) {
        e.preventDefault();
        mostrarCarritoModal();
    });

    function mostrarCarritoModal() {
        let carrito = getCarrito();
        if (carrito.length === 0) {
            $("#listaCarritoProductos").html("<li>El carrito está vacío.</li>");
            $("#btnComprarCarritoFinal").hide();
        } else {
            let itemsHtml = "";
            let pendientes = carrito.length;
            carrito.forEach(function (item, idx) {
                $.post('Modelo/obtenerProducto.php', { id: item.id }, function (resp) {
                    let prod = JSON.parse(resp);
                    itemsHtml += `<li>
                        <b>${prod.marca} ${prod.modelo}</b> - Cantidad: ${item.cantidad}
                        <button class="btnEliminarDelCarrito" data-idx="${idx}" style="margin-left:10px;color:#e74c3c;">Eliminar</button>
                    </li>`;
                    pendientes--;
                    if (pendientes === 0) {
                        $("#listaCarritoProductos").html(itemsHtml);
                        $("#btnComprarCarritoFinal").show();
                    }
                });
            });
        }
        $("#modalCarrito").css("display", "flex");
        actualizarCantidadCarrito();
    }

    // Cerrar modal del carrito
    $(document).on('click', '#cerrarModalCarrito', function () {
        $("#modalCarrito").hide();
    });

    // Eliminar producto del carrito
    $(document).on('click', '.btnEliminarDelCarrito', function () {
        let idx = $(this).data('idx');
        let carrito = getCarrito();
        carrito.splice(idx, 1);
        setCarrito(carrito);
        mostrarCarritoModal();
        actualizarCantidadCarrito();
    });

    // Comprar todo el carrito
    $(document).on('click', '#btnComprarCarritoFinal', function () {
        $.get('Modelo/estaLogueado.php', function (resp) {
            if (resp.trim() === "ok") {
                let carrito = getCarrito();
                if (carrito.length === 0) {
                    Swal.fire({
                        title: "Carrito vacío",
                        text: "El carrito está vacío.",
                        icon: "warning"
                    });
                    return;
                }
                let pedidosRealizados = 0;
                let errores = 0;
                carrito.forEach(function (item) {
                    $.ajax({
                        url: "index.php?accion=addPedido",
                        type: "POST",
                        data: {
                            nombre: window.clienteNombre || "",
                            correo: window.clienteCorreo || "",
                            telefono: "",
                            direccion: "",
                            producto: item.id,
                            cantidad: item.cantidad
                        },
                        async: false,
                        success: function () { pedidosRealizados++; },
                        error: function () { errores++; }
                    });
                });
                setCarrito([]); // Limpia el carrito después de comprar
                $("#modalCarrito").hide();
                actualizarCantidadCarrito();
                if (errores === 0) {
                    Swal.fire({
                        title: "¡Compra realizada!",
                        text: "¡Compra realizada con éxito!",
                        icon: "success"
                    }).then(() => {
                        window.location = "index.php?accion=vista";
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Algunos pedidos no se pudieron realizar.",
                        icon: "error"
                    });
                }
            } else {
                Swal.fire({
                    title: "Inicia sesión",
                    text: "Debes iniciar sesión para comprar.",
                    icon: "warning"
                }).then(() => {
                    window.location = "index.php?accion=iniciarCliente";
                });
            }
        });
    });

    // Añadir al carrito desde la vista de producto
    $(document).on('click', '#btnSolicitarCompra', function () {
        $.get('Modelo/estaLogueado.php', function (resp) {
            if (resp.trim() === "ok") {
                const id = getQueryParam('id');
                const cantidad = parseInt($("#cantidadProducto").val()) || 1;
                let carrito = getCarrito();
                const idx = carrito.findIndex(p => p.id == id);
                if (idx >= 0) {
                    carrito[idx].cantidad += cantidad;
                } else {
                    carrito.push({ id: id, cantidad: cantidad });
                }
                setCarrito(carrito);
                Swal.fire({
                    title: "¡Producto añadido!",
                    text: "Producto añadido al carrito",
                    icon: "success"
                });
                actualizarCantidadCarrito();
            } else {
                Swal.fire({
                    title: "Inicia sesión",
                    text: "Debes iniciar sesión para continuar",
                    icon: "warning"
                }).then(() => {
                    window.location = "index.php?accion=iniciarCliente";
                });
            }
        });
    });

    // Actualiza el contador al cargar la página
    actualizarCantidadCarrito();

    // Utilidad para obtener parámetros de la URL
    function getQueryParam(name) {
        const url = new URL(window.location.href);
        return url.searchParams.get(name);
    }

    // =========================
    // 8. Cambiar estado de pedido (admin)
    // =========================

    // Cambiar estado con switch (checkbox)
    $(document).on('change', '.cbx-entregado', function() {
        const id = $(this).data('id');
        const estado = $(this).is(':checked') ? "Entregado" : "Pendiente";
        $.post('index.php?accion=cambiarEstadoPedido', { id: id, estado: estado }, function(resp) {
            Swal.fire({
                title: "Estado actualizado",
                text: estado === "Entregado" ? "Pedido marcado como entregado." : "Pedido marcado como pendiente.",
                icon: "success"
            });
        });
    });

    // Cambiar estado con select (múltiples estados)
    $(document).on('change', '.select-estado-pedido', function() {
        const id = $(this).data('id');
        const estado = $(this).val();
        $.post('index.php?accion=cambiarEstadoPedido', { id: id, estado: estado }, function(resp) {
            Swal.fire({
                title: "Estado actualizado",
                text: "El pedido ahora está: " + estado,
                icon: "success"
            });
        });
    });

    // =========================
    // 9. Dashboard (admin)
    // =========================

    // Pedidos por mes
    if (document.getElementById('pedidosMes')) {
        $.get('Modelo/dashboard_pedidos_mes.php', function(resp) {
            const data = JSON.parse(resp);
            new Chart(document.getElementById('pedidosMes'), {
                type: 'bar',
                data: {
                    labels: data.meses,
                    datasets: [{
                        label: 'Pedidos',
                        data: data.cantidades,
                        backgroundColor: '#4F2EDC'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    }

    // Productos más vendidos
    if (document.getElementById('productosMasVendidos')) {
        $.get('Modelo/dashboard_productos_mas_vendidos.php', function(resp) {
            const data = JSON.parse(resp);
            new Chart(document.getElementById('productosMasVendidos'), {
                type: 'bar',
                data: {
                    labels: [data.nombre],
                    datasets: [{
                        label: 'Pedidos',
                        data: [data.pedidos],
                        backgroundColor: '#947ADA'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    }

    // Cliente más frecuente
    if (document.getElementById('clienteFrecuente')) {
        $.get('Modelo/dashboard_cliente_frecuente.php', function(resp) {
            const data = JSON.parse(resp);
            $("#clienteFrecuente").html(
                `<strong>${data.nombre}</strong><br>
                <small>${data.correo}</small><br>
                Pedidos: <b>${data.pedidos}</b>`
            );
        });
    }

    // Producto menos vendido
    if (document.getElementById('productoMenosVendido')) {
        $.get('Modelo/dashboard_producto_menos_vendido.php', function(resp) {
            const data = JSON.parse(resp);
            $("#productoMenosVendido").html(
                `<strong>${data.producto}</strong><br>
                Vendidos: <b>${data.cantidad}</b>`
            );
        });
    }

    // Redirigir al hacer clic en "Ver producto"
    $(document).on('click', '.btn-ver-producto', function () {
        const id = $(this).data('id');
        window.location = 'index.php?accion=vistaProducto&id=' + id;
    });


    let paginaActual = 1;
    let porPagina = 6;

    function mostrarProductos(nombre = "", categoria = "", pagina = 1, por_pagina = 6) {
        $.post("Modelo/MostrarProductos.php", {
            nombre: nombre,
            categoria: categoria,
            pagina: pagina,
            por_pagina: por_pagina
        }, function (respuesta) {
            $("#resultadoProductos").html(respuesta);
        });
    }


    // Al hacer clic en paginación
    $(document).on('click', '.pagina-link', function (e) {
        e.preventDefault();
        paginaActual = parseInt($(this).data('pagina'));
        mostrarProductos($("#buscarProducto").val(), $("#categoriaFiltro").val(), paginaActual, porPagina);
    });

    // Modifica el submit de filtros para usar la paginación
    $("#filtrosForm").submit(function (e) {
        e.preventDefault();
        paginaActual = 1;
        mostrarProductos($("#buscarProducto").val(), $("#categoriaFiltro").val(), paginaActual, porPagina);
    });

    // Inicializa con los valores actuales
    mostrarProductos("", "", paginaActual, porPagina);

    
});