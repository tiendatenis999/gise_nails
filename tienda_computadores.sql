-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-07-2025 a las 18:10:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_computadores`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Portátiles'),
(2, 'Computadores de escritorio'),
(3, 'Repuestos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_producto`
--

CREATE TABLE `imagenes_producto` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `ruta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes_producto`
--

INSERT INTO `imagenes_producto` (`id`, `id_producto`, `ruta`) VALUES
(9, 10, 'uploads/6866c37d93520_portatil_lenovo.png'),
(10, 10, 'uploads/6866c37d93b93_portatil2_lenovo.jpg'),
(11, 10, 'uploads/6866c37d9495e_portatil3_lenovo.jpg'),
(12, 11, 'uploads/6866c3d496d9e_asus_portatil.png'),
(13, 11, 'uploads/6866c3d49730a_asus2_portatil.jpg'),
(14, 12, 'uploads/6866c43ce0f71_portatil_hp.jpg'),
(15, 12, 'uploads/6866c43ce1eb0_portatil2_hp.jpg'),
(16, 13, 'uploads/6866c48145ec9_asus_escritorio.jpg'),
(17, 13, 'uploads/6866c48146d2a_asus2_escritorio.jpg'),
(18, 14, 'uploads/6866c4f098f49_lenovo_escritorio.jpg'),
(19, 14, 'uploads/6866c4f09a0c3_lenovo2_escritorio.jpg'),
(20, 14, 'uploads/6866c4f09ade1_lenovo3_escritorio.jpg'),
(21, 15, 'uploads/6866c536e0fa4_escritorio_hp.jpeg'),
(22, 15, 'uploads/6866c536e16c1_escritorio2_hp.png'),
(23, 15, 'uploads/6866c536e1f38_escritorio3_hp.jpg'),
(24, 16, 'uploads/6866c58376b7c_repuesto3_lenovo.jpg'),
(25, 17, 'uploads/6866c5db66a6b_repuesto_lenovo.jpg'),
(26, 18, 'uploads/6866c60e3aa6a_repuesto2_lenovo.jpg'),
(27, 19, 'uploads/6866c64478d98_repuesto_hp.webp'),
(28, 20, 'uploads/6866c6a5645c6_repuesto2_hp.webp'),
(29, 21, 'uploads/6866c6e688c66_repuesto_asus.jpg'),
(30, 22, 'uploads/6866c7190866d_repuesto2_asus.webp'),
(31, 23, 'uploads/6866c74badef7_repuesto3_asus.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Pendiente','Enviado','Entregado','Cancelado') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `id_producto`, `cantidad`, `fecha`, `estado`) VALUES
(30, 9, 10, 2, '2025-07-03 23:32:47', 'Entregado'),
(31, 9, 11, 1, '2025-07-03 23:32:47', 'Pendiente'),
(32, 2, 10, 2, '2025-07-04 00:15:24', 'Pendiente'),
(33, 2, 14, 1, '2025-07-04 00:23:08', 'Entregado'),
(34, 2, 10, 1, '2025-07-04 05:00:40', 'Entregado'),
(35, 9, 20, 1, '2025-07-06 03:58:48', 'Cancelado'),
(36, 9, 19, 2, '2025-07-06 03:58:48', 'Entregado'),
(37, 9, 12, 1, '2025-07-06 03:58:48', 'Enviado'),
(38, 10, 12, 1, '2025-07-06 05:03:20', 'Pendiente'),
(39, 10, 11, 1, '2025-07-06 05:03:20', 'Pendiente'),
(40, 10, 17, 1, '2025-07-06 05:03:20', 'Pendiente'),
(41, 10, 13, 2, '2025-07-06 05:18:06', 'Enviado'),
(42, 10, 17, 1, '2025-07-06 05:18:06', 'Entregado'),
(43, 10, 14, 1, '2025-07-06 05:18:06', 'Entregado'),
(44, 10, 12, 1, '2025-07-06 05:34:24', 'Entregado'),
(45, 10, 13, 1, '2025-07-06 07:31:07', 'Cancelado'),
(46, 10, 11, 1, '2025-07-06 07:31:07', 'Pendiente'),
(47, 10, 16, 1, '2025-07-06 07:31:07', 'Entregado'),
(48, 10, 11, 1, '2025-07-06 08:59:19', 'Cancelado'),
(49, 10, 13, 3, '2025-07-06 08:59:45', 'Enviado'),
(50, 9, 12, 1, '2025-07-06 09:31:19', 'Pendiente'),
(51, 9, 14, 1, '2025-07-06 09:31:19', 'Pendiente'),
(52, 9, 18, 1, '2025-07-06 09:31:19', 'Entregado'),
(53, 9, 22, 1, '2025-07-06 09:31:19', 'Pendiente'),
(54, 10, 11, 1, '2025-07-07 17:27:06', 'Pendiente'),
(55, 10, 23, 1, '2025-07-07 17:27:06', 'Pendiente'),
(56, 10, 11, 1, '2025-07-08 04:02:29', 'Pendiente'),
(57, 10, 12, 1, '2025-07-08 16:15:28', 'Pendiente'),
(58, 10, 21, 1, '2025-07-08 16:15:28', 'Entregado'),
(59, 10, 11, 1, '2025-07-08 17:07:10', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `tipo` enum('Computador','Repuesto') NOT NULL,
  `especificaciones` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `marca`, `modelo`, `tipo`, `especificaciones`, `precio`, `id_categoria`) VALUES
(10, 'Lenovo', '2024', 'Repuesto', 'Portatil Lenovo, 8gb Ram, Intel Icore5', 1200000.00, 1),
(11, 'Asus', '2025', 'Computador', 'Portatil Asus, 12Gb Ram, 2TB', 2800000.00, 1),
(12, 'Hp', '2024', 'Computador', 'Portatil Hp, 8Gb Ram, 1Tb', 2000000.00, 1),
(13, 'Asus', '2024', 'Computador', 'Computador de mesa Asus, 12Gb ram, 2Tb', 3500000.00, 2),
(14, 'Lenovo', '2024', 'Computador', 'Computador de mesa Lenovo, 8Gb ram, 1Tb', 2500000.00, 2),
(15, 'Hp', '2023', 'Computador', 'Computador de mesa Hp, 8Gb Ram, 1Tb', 2300000.00, 2),
(16, 'Lenovo', '2025', 'Repuesto', 'Cargador Original', 120000.00, 3),
(17, 'Lenovo', '2015', 'Repuesto', 'Bateria Original', 400000.00, 3),
(18, 'Lenovo', '2022', 'Repuesto', 'Tarjeta Ram', 500000.00, 3),
(19, 'Hp', '2022', 'Repuesto', 'Ventilador Procesador', 350000.00, 3),
(20, 'Hp', '2023', 'Repuesto', 'Fuente de Poder', 320000.00, 3),
(21, 'Asus', '2024', 'Repuesto', 'Tarjeta Grafica', 550000.00, 3),
(22, 'Asus', '2025', 'Repuesto', 'Bateria Original', 600000.00, 3),
(23, 'Asus', '2024', 'Repuesto', 'Disco Duro', 450000.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contraseña`, `rol`) VALUES
(1, 'Santiago Paccini', 'admin@tenis.com', 'admin', 'admin'),
(2, 'Santiago', 'santiago.paccini.1403@gmail.com', '123', 'cliente'),
(8, 'juan', 'clinicaodontologicadeltolima4@gmail.com', '', 'cliente'),
(9, 'Juan Sebastian Arcila Villamarin', 'giselav489@gmail.com', '123', 'cliente'),
(10, 'lorien', 'lorien@gmail.com', '123', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes_producto`
--
ALTER TABLE `imagenes_producto`
  ADD CONSTRAINT `imagenes_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
