# Tienda de Computadores y Repuestos

Proyecto web completo para la gestión y venta de computadores y repuestos, desarrollado en PHP, MySQL, JavaScript (jQuery), HTML5 y CSS3.

---

## Tabla de Contenidos

- [Descripción General](#descripción-general)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Modelo de Base de Datos](#modelo-de-base-de-datos)
- [Funcionalidades](#funcionalidades)
- [Guía de Uso](#guía-de-uso)
- [Notas Técnicas](#notas-técnicas)
- [Créditos](#créditos)

---

## Descripción General

Este sistema permite administrar un catálogo de computadores y repuestos, gestionar usuarios (clientes y administradores), realizar compras, administrar pedidos y categorías, y soporta múltiples imágenes por producto.  
Incluye panel de administración, dashboard estadístico, carrito de compras persistente, notificaciones modernas con SweetAlert2 y gestión visual de productos, categorías y pedidos.  
El frontend es completamente **responsive** y moderno, con tarjetas alineadas y filtros avanzados.

**Novedades recientes:**
- Carrito persistente por usuario (localStorage)
- Filtros avanzados y visuales para productos y pedidos (cliente y admin)
- Estados de pedido: Pendiente, Enviado, Entregado, Cancelado
- Alertas inteligentes al eliminar productos/categorías con dependencias
- Dashboard visual con Chart.js
- Formularios flotantes y overlays modernos
- Código y estilos organizados y comentados

---

## Requisitos

- PHP >= 8.0
- MySQL/MariaDB
- Apache (XAMPP recomendado)
- Navegador web moderno

---

## Instalación

1. **Clona o descarga el repositorio en tu servidor local (ej: XAMPP/htdocs):**
   ```bash
   git clone https://github.com/Paccini/tienda_computadores.git
   ```

2. **Importa la base de datos:**
   - Crea una base de datos llamada `tienda_computadores`.
   - Importa el archivo SQL proporcionado (`tienda_computadores.sql`).

3. **Configura la conexión a la base de datos:**
   - Edita `Modelo/Conexion.php` si tu usuario/contraseña de MySQL es diferente.

4. **Asegúrate de que la carpeta `uploads/` exista y tenga permisos de escritura para subir imágenes.**

5. **Inicia Apache y MySQL desde XAMPP.**

6. **Accede desde tu navegador:**
   ```
   http://localhost/tienda_computadores/index.php
   ```

---

## Estructura del Proyecto

```
tienda_computadores/
│
├── Controlador/
│   └── Controlador.php
├── Modelo/
│   ├── Conexion.php
│   ├── productos.php
│   ├── pedidos.php
│   ├── gestorProductos.php
│   ├── InicioSesion.php
│   ├── MostrarProductos.php
│   ├── MostrarCategorias.php
│   ├── listarProductos.php
│   ├── listarPedidos.php
│   ├── obtenerProductoCompleto.php
│   ├── obtenerProducto.php
│   ├── dashboard_productos_mas_vendidos.php
│   ├── dashboard_producto_menos_vendido.php
│   ├── dashboard_pedidos_mes.php
│   ├── dashboard_cliente_frecuente.php
│   └── ...
├── Vista/
│   ├── css/
│   │   └── styles.css
│   ├── script/
│   │   └── script.js
│   └── html/
│       ├── index.html
│       ├── loginAdmin.html
│       ├── loginCliente.html
│       ├── RegistroCliente.html
│       ├── administrador.html
│       ├── categorias.html
│       ├── pedidos.html
│       ├── misPedidos.html
│       ├── vistaProducto.html
│       ├── dashboard.html
│       └── ...
├── uploads/
│   └── (imágenes de productos)
├── index.php
└── README.md
```

---

## Modelo de Base de Datos

### Tablas principales

- **usuarios**: id, nombre, correo, contraseña, rol (admin/cliente)
- **productos**: id, marca, modelo, tipo (Computador/Repuesto), especificaciones, precio, id_categoria
- **imagenes_producto**: id, id_producto (FK), ruta
- **categorias**: id, nombre
- **pedidos**: id, id_usuario (FK), id_producto (FK), cantidad, fecha, estado (`Pendiente`, `Enviado`, `Entregado`, `Cancelado`)

**Nota:** La tabla `imagenes_producto` permite asociar múltiples imágenes a cada producto.

---

## Funcionalidades

### Cliente

- Registro e inicio de sesión.
- Visualización de catálogo de productos y repuestos con tarjetas alineadas y diseño responsive.
- Filtro avanzado por nombre y categoría.
- Visualización detallada de cada producto (todas sus imágenes y especificaciones).
- Solicitud de compra (requiere sesión iniciada).
- **Carrito de compras persistente por usuario** (localStorage, se asocia al correo del cliente).
- Modal de carrito con opción de comprar todo o eliminar productos.
- Visualización de pedidos realizados y su estado actual.
- Filtros visuales para buscar pedidos por producto y estado.
- Notificaciones modernas con SweetAlert2.

### Administrador

- Login de administrador.
- Gestión de productos (crear, editar, eliminar, subir múltiples imágenes).
- Gestión de categorías (crear, editar, eliminar).
- Gestión de pedidos (cambiar estado: Pendiente, Enviado, Entregado, Cancelado).
- **Dashboard con estadísticas visuales**: pedidos por mes, productos más/menos vendidos, cliente más frecuente (usando Chart.js).
- Tablas administrativas con diseño moderno y acciones por iconos.
- Filtros avanzados en productos y pedidos.
- Formularios flotantes y overlays modernos para edición y registro.
- **Alertas inteligentes:**  
  - No permite eliminar productos con pedidos asociados (alerta explicativa).
  - No permite eliminar categorías con productos asociados (alerta explicativa).

---

## Guía de Uso

### 1. Acceso

- **Zona Cliente:**  
  `index.php?accion=iniciarCliente`
- **Zona Admin:**  
  `index.php?accion=zonaAdmin`

- **Credenciales de administrador por defecto:**  
  - **Correo:** `admin@tenis.com`  
  - **Contraseña:** `admin`

### 2. Catálogo

- Desde la página principal, puedes ver todos los productos.
- Usa los filtros para buscar por nombre o categoría.
- Haz clic en "Ver producto" para ver detalles e imágenes.
- Añade productos al carrito y gestiona tu compra desde el modal de carrito.

### 3. Registro e Inicio de Sesión

- Los clientes pueden registrarse y luego iniciar sesión para comprar.
- Los administradores deben iniciar sesión desde la zona admin.

### 4. Gestión de Productos y Categorías

- Los administradores pueden agregar, editar y eliminar productos y categorías desde el panel de administración.
- Los formularios de gestión muestran notificaciones modernas al guardar, editar o eliminar.
- Se pueden subir múltiples imágenes por producto.
- No se pueden eliminar productos con pedidos asociados ni categorías con productos asociados (el sistema lo indica con una alerta).

### 5. Pedidos

- Los clientes pueden solicitar compras (si están logueados).
- Los clientes pueden ver el estado de sus pedidos y filtrarlos por producto o estado.
- Los administradores pueden ver y cambiar el estado de los pedidos (Pendiente, Enviado, Entregado, Cancelado) con select visual y filtros avanzados.

### 6. Dashboard

- Accede a estadísticas de ventas, productos y clientes desde el panel de administración.
- Visualización gráfica de datos con Chart.js.

---

## Notas Técnicas

- **Rutas amigables:** El sistema funciona a través de `index.php?accion=...`. No accedas directamente a los archivos `.html`.
- **Imágenes:** Se almacenan en la carpeta `uploads/` y se asocian en la tabla `imagenes_producto`.
- **Sesiones:** El sistema usa sesiones PHP para mantener la autenticación de clientes y administradores.
- **AJAX:** Se utiliza jQuery para la carga dinámica de productos, categorías, pedidos y validaciones.
- **Notificaciones:** Se utiliza SweetAlert2 para alertas y confirmaciones modernas.
- **Carrito:** El carrito de compras se almacena en localStorage y se asocia al usuario logueado.
- **Tablas administrativas:** Todas las tablas (productos, categorías, pedidos) tienen diseño moderno y acciones por iconos.
- **Frontend responsive:** Todo el sitio es adaptable a dispositivos móviles y tablets.
- **Código organizado y comentado:** CSS y JS estructurados por bloques funcionales.

---

## Créditos

Desarrollado por Juan Sebastian Arcila y Santiago Paccini.  
Proyecto académico - Tienda de Computadores y Repuestos.
Grupo Paccila