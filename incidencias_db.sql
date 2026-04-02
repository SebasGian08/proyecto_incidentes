-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-04-2026 a las 02:34:49
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `incidencias_db`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_excel_inventario` (IN `p_inicio` DATE, IN `p_fin` DATE)   BEGIN
    SELECT
        p.codigo_producto,
        p.descripcion,
        p.stock,
        p.stock_minimo,
        IFNULL(SUM(cd.cantidad), 0) AS total_comprado,
        IFNULL(SUM(pd.cantidad), 0) AS total_vendido
    FROM productos p
    LEFT JOIN compra_detalle cd 
        ON cd.id_producto = p.id_producto
    LEFT JOIN compra c 
        ON c.id_compra = cd.id_compra
        AND c.fecha_compra BETWEEN p_inicio AND p_fin
    LEFT JOIN pedidos_detalle pd 
        ON pd.id_producto = p.id_producto
    LEFT JOIN pedidos pe 
        ON pe.id_pedido = pd.id_pedido
        AND pe.fecha_pedido BETWEEN p_inicio AND p_fin
        AND pe.estado_pedido = 'ENTREGADO'
    WHERE p.estado = 1
    GROUP BY p.id_producto, p.codigo_producto, p.descripcion, p.stock, p.stock_minimo
    ORDER BY p.descripcion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_excel_kardex` (IN `p_inicio` DATE, IN `p_fin` DATE)   BEGIN
    SELECT
        k.fecha_movimiento,
        p.descripcion AS producto,
        k.tipo_movimiento,
        k.motivo,
        k.cantidad,
        k.stock_anterior,
        k.stock_nuevo,
        k.costo_unitario,
        k.costo_total
    FROM kardex k
    INNER JOIN productos p ON p.id_producto = k.id_producto
    WHERE k.fecha_movimiento BETWEEN p_inicio AND p_fin
    ORDER BY k.fecha_movimiento, p.descripcion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_excel_kardex_listado` (IN `p_inicio` DATE, IN `p_fin` DATE, IN `p_id_tipo_movimiento` INT)   BEGIN
    SELECT
        k.fecha_movimiento,
        p.descripcion AS producto,
        tm.nombre AS tipo_movimiento,
        k.cantidad,
        k.stock_anterior,
        k.stock_nuevo,
        k.costo_unitario,
        k.costo_total
    FROM kardex k
    INNER JOIN productos p 
        ON p.id_producto = k.id_producto
    INNER JOIN tipo_movimiento_kardex tm
        ON tm.id_tipo_movimiento = k.id_tipo_movimiento
    WHERE DATE(k.fecha_movimiento) BETWEEN p_inicio AND p_fin
      AND (p_id_tipo_movimiento IS NULL 
           OR k.id_tipo_movimiento = p_id_tipo_movimiento)
    ORDER BY k.fecha_movimiento, p.descripcion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_excel_rentabilidad` (IN `p_inicio` DATE, IN `p_fin` DATE)   BEGIN
    SELECT
        p.codigo_producto,
        p.descripcion,
        SUM(pd.cantidad) AS cantidad_vendida,
        SUM(pd.total) AS ingreso_total,
        SUM(pd.cantidad * p.precio_compra) AS costo_total,
        (SUM(pd.total) - SUM(pd.cantidad * p.precio_compra)) AS margen
    FROM pedidos_detalle pd
    INNER JOIN pedidos pe ON pe.id_pedido = pd.id_pedido
    INNER JOIN productos p ON p.id_producto = pd.id_producto
    WHERE pe.estado_pedido = 'ENTREGADO'
      AND pe.fecha_pedido BETWEEN p_inicio AND p_fin
    GROUP BY p.id_producto, p.codigo_producto, p.descripcion
    ORDER BY margen DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_excel_sin_rotacion` (IN `p_inicio` DATE, IN `p_fin` DATE)   BEGIN
    SELECT
        p.codigo_producto,
        p.descripcion,
        p.stock,
        p.stock_minimo,
        p.precio_compra,
        p.precio_venta,
        DATEDIFF(
            p_fin,
            IFNULL(MAX(pe.fecha_pedido), p_inicio)
        ) AS dias_sin_rotacion
    FROM productos p
    LEFT JOIN pedidos_detalle pd ON pd.id_producto = p.id_producto
    LEFT JOIN pedidos pe 
        ON pe.id_pedido = pd.id_pedido
        AND pe.estado_pedido = 'ENTREGADO'
        AND pe.fecha_pedido <= p_fin
    WHERE p.estado = 1
    GROUP BY
        p.id_producto,
        p.codigo_producto,
        p.descripcion,
        p.stock,
        p.stock_minimo,
        p.precio_compra,
        p.precio_venta
    HAVING MAX(pe.fecha_pedido) IS NULL
        OR MAX(pe.fecha_pedido) < p_inicio
    ORDER BY p.descripcion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_kardex_valorizado` ()   BEGIN
    SELECT 
        p.descripcion AS descripcion,
        tmk.nombre AS tipo_movimiento,  
        tmk.codigo AS codigo_tipo,     
        SUM(k.cantidad) AS cantidad_total,
        SUM(k.costo_total) AS valor_total
    FROM kardex k
    JOIN productos p ON p.id_producto = k.id_producto
    JOIN tipo_movimiento_kardex tmk ON tmk.id_tipo_movimiento = k.id_tipo_movimiento
    GROUP BY p.descripcion, tmk.nombre, tmk.codigo
    ORDER BY p.descripcion, tmk.codigo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_margen_producto` ()   BEGIN
    SELECT 
        p.descripcion,
        SUM(pd.total) AS ingreso,
        SUM(pd.cantidad * p.precio_compra) AS costo,
        (SUM(pd.total) - SUM(pd.cantidad * p.precio_compra)) AS margen
    FROM pedidos_detalle pd
    JOIN pedidos pe ON pe.id_pedido = pd.id_pedido
    JOIN productos p ON p.id_producto = pd.id_producto
    WHERE pe.estado_pedido = 'ENTREGADO'
    GROUP BY p.descripcion
    ORDER BY margen DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_rotacion_inventario` ()   BEGIN
    SELECT 
        p.id_producto,
        p.descripcion,
        SUM(pd.cantidad) AS total_vendido,
        SUM(pd.total) AS ingreso_total,
        p.stock
    FROM pedidos_detalle pd
    JOIN pedidos pe ON pe.id_pedido = pd.id_pedido
    JOIN productos p ON p.id_producto = pd.id_producto
    WHERE pe.estado_pedido = 'ENTREGADO'
    GROUP BY p.id_producto, p.descripcion, p.stock
    ORDER BY total_vendido DESC;
    
    
    -- SP 1: Rotación de inventario
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_stock_critico` ()   BEGIN
    SELECT 
        p.id_producto,
        p.descripcion,
        p.stock,
        p.stock_minimo
    FROM productos p
    WHERE p.stock <= p.stock_minimo
      AND p.estado = 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activos_ti`
--

CREATE TABLE `activos_ti` (
  `id` bigint(20) NOT NULL,
  `codigo_patrimonial` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `tipo_id` int(11) NOT NULL,
  `usuario_responsable_id` bigint(20) DEFAULT NULL,
  `criticidad_id` int(11) DEFAULT NULL,
  `confidencialidad_id` int(11) DEFAULT NULL,
  `ubicacion_id` int(11) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `activos_ti`
--

INSERT INTO `activos_ti` (`id`, `codigo_patrimonial`, `nombre`, `numero_serie`, `tipo_id`, `usuario_responsable_id`, `criticidad_id`, `confidencialidad_id`, `ubicacion_id`, `estado_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, '123', 'AAAA', '123AAA', 1, 1, 1, 1, 1, 2, '2026-03-21 16:19:13', '2026-03-21 16:19:13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_incidentes`
--

CREATE TABLE `historial_incidentes` (
  `id` bigint(20) NOT NULL,
  `incidente_id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_areas`
--

CREATE TABLE `maestro_areas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo_interno` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_confidencialidad`
--

CREATE TABLE `maestro_confidencialidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_confidencialidad`
--

INSERT INTO `maestro_confidencialidad` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Confidencial', '2026-03-13 22:32:32', '2026-03-13 22:32:32', NULL),
(2, 'Interno', '2026-03-13 22:32:32', '2026-03-13 22:32:32', NULL),
(3, 'Público', '2026-03-13 22:32:32', '2026-03-13 22:32:32', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_criticidad`
--

CREATE TABLE `maestro_criticidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_criticidad`
--

INSERT INTO `maestro_criticidad` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Alta', '2026-03-13 22:32:32', '2026-03-13 22:32:32', NULL),
(2, 'Media', '2026-03-13 22:32:32', '2026-03-13 22:32:32', NULL),
(3, 'Baja', '2026-03-13 22:32:32', '2026-03-13 22:32:32', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_estado_activo`
--

CREATE TABLE `maestro_estado_activo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_estado_activo`
--

INSERT INTO `maestro_estado_activo` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Operativo', '2026-03-13 22:38:59', '2026-03-13 22:38:59', NULL),
(2, 'Baja', '2026-03-13 22:38:59', '2026-03-13 22:38:59', NULL),
(3, 'Mantenimiento', '2026-03-13 22:38:59', '2026-03-13 22:38:59', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_estado_ticket`
--

CREATE TABLE `maestro_estado_ticket` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_estado_ticket`
--

INSERT INTO `maestro_estado_ticket` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Abierto', '2026-03-13 22:44:50', '2026-03-13 22:44:50', NULL),
(2, 'En Progreso', '2026-03-13 22:44:50', '2026-03-13 22:44:50', NULL),
(3, 'Cerrado', '2026-03-13 22:44:50', '2026-03-13 22:44:50', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_severidad`
--

CREATE TABLE `maestro_severidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `sla_minutos` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_severidad`
--

INSERT INTO `maestro_severidad` (`id`, `nombre`, `sla_minutos`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Crítica', 120, '2026-03-13 22:37:04', '2026-04-02 00:25:55', NULL),
(2, 'Alta', 360, '2026-03-13 22:37:04', '2026-04-02 00:25:55', NULL),
(3, 'Media', 720, '2026-03-13 22:37:04', '2026-04-02 00:25:55', NULL),
(4, 'Baja', 1440, '2026-03-13 22:37:04', '2026-04-02 00:25:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_tipos_activos`
--

CREATE TABLE `maestro_tipos_activos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_tipos_activos`
--

INSERT INTO `maestro_tipos_activos` (`id`, `nombre`, `codigo`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'prueba', '123', 'aaaa', '2026-03-21 16:13:52', '2026-03-21 16:13:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_ubicacion_fisica`
--

CREATE TABLE `maestro_ubicacion_fisica` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestro_ubicacion_fisica`
--

INSERT INTO `maestro_ubicacion_fisica` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ALMACEN PRINCIPAL', '2026-03-21 16:18:56', '2026-03-21 16:18:56', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DESARROLLADOR', '2026-01-08 20:53:57', NULL, NULL),
(2, 'ADMINISTRADOR', '2026-01-08 20:53:57', NULL, NULL),
(3, 'OPERADOR', '2026-01-08 20:53:57', NULL, NULL),
(4, 'USUARIO', '2026-01-08 20:53:57', NULL, NULL),
(5, 'VENDEDOR', '2026-01-08 20:53:57', NULL, '2026-01-08 20:53:57'),
(6, 'CLIENTE ASOCIADO', '2026-01-08 20:53:57', NULL, '2026-01-08 20:53:57'),
(7, 'MOTORIZADO', '2026-01-08 20:53:57', NULL, '2026-01-08 20:53:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets_incidentes`
--

CREATE TABLE `tickets_incidentes` (
  `id` bigint(20) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `severidad_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `activo_id` bigint(20) DEFAULT NULL,
  `usuario_reporta_id` bigint(20) DEFAULT NULL,
  `tecnico_asignado_id` bigint(20) DEFAULT NULL,
  `evidencia` varchar(255) DEFAULT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `profile_id` bigint(20) NOT NULL,
  `nombres` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `documento_identidad` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `ecommerce_nombre` varchar(150) DEFAULT NULL,
  `estado` varchar(11) NOT NULL DEFAULT '1',
  `online` varchar(5) DEFAULT '0',
  `inicio_sesion` varchar(250) DEFAULT NULL,
  `cerrar_sesion` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `observaciones_perfil` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `profile_id`, `nombres`, `email`, `usuario`, `email_verified_at`, `password`, `telefono`, `direccion`, `documento_identidad`, `remember_token`, `pais`, `ecommerce_nombre`, `estado`, `online`, `inicio_sesion`, `cerrar_sesion`, `created_at`, `updated_at`, `deleted_at`, `apellidos`, `cargo`, `area_id`, `observaciones_perfil`) VALUES
(1, 1, 'Sebastian Vásquez', 'admin', NULL, NULL, '$2y$10$KABbWAD63KNjBVg/eIbNzeg7JzwT7bpwTeERNxV54seGFWWO/Zwea', NULL, NULL, NULL, NULL, NULL, NULL, '1', '1', '2026-04-01 18:07:29', '26-04-01 06:07:22', NULL, '2026-04-01 23:07:29', NULL, '', NULL, NULL, NULL),
(2, 1, 'Daniel Mature', 'daniel@essentiumgroup.com', 'daniel123', NULL, '$2y$10$X2tuHlxZnQ2a3Nz1ur3eO.TkyB6lqH0hihAW15jeft1ZzAoRzqitK', '987654321', NULL, NULL, NULL, 'Perú', 'essentiumgroup', '1', '0', '2026-04-01 12:57:59', '26-04-01 12:58:16', '2025-10-16 12:50:18', '2026-04-01 17:58:16', NULL, '', NULL, NULL, NULL),
(3, 7, 'MOTORIZADO 1', 'motorizado1', NULL, NULL, '$2y$10$.cMu0VIgREEk7SB00Wvk8u2QDom8.nYwRVNV3qbRkzcJNQ2f6uPoa', NULL, NULL, NULL, NULL, NULL, NULL, '2', '0', '2026-02-13 18:14:45', '26-02-13 06:14:53', '2025-12-27 19:23:01', '2026-03-13 21:01:38', NULL, '', NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activos_ti`
--
ALTER TABLE `activos_ti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_patrimonial` (`codigo_patrimonial`),
  ADD UNIQUE KEY `numero_serie` (`numero_serie`),
  ADD KEY `fk_activo_tipo` (`tipo_id`),
  ADD KEY `fk_activo_criticidad` (`criticidad_id`),
  ADD KEY `fk_activo_confidencialidad` (`confidencialidad_id`),
  ADD KEY `fk_activo_estado` (`estado_id`),
  ADD KEY `fk_activo_ubicacion` (`ubicacion_id`);

--
-- Indices de la tabla `historial_incidentes`
--
ALTER TABLE `historial_incidentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_historial_incidente` (`incidente_id`);

--
-- Indices de la tabla `maestro_areas`
--
ALTER TABLE `maestro_areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `codigo_interno` (`codigo_interno`);

--
-- Indices de la tabla `maestro_confidencialidad`
--
ALTER TABLE `maestro_confidencialidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `maestro_criticidad`
--
ALTER TABLE `maestro_criticidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `maestro_estado_activo`
--
ALTER TABLE `maestro_estado_activo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `maestro_estado_ticket`
--
ALTER TABLE `maestro_estado_ticket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `maestro_severidad`
--
ALTER TABLE `maestro_severidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `maestro_tipos_activos`
--
ALTER TABLE `maestro_tipos_activos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maestro_ubicacion_fisica`
--
ALTER TABLE `maestro_ubicacion_fisica`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tickets_incidentes`
--
ALTER TABLE `tickets_incidentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ticket_severidad` (`severidad_id`),
  ADD KEY `fk_ticket_estado` (`estado_id`),
  ADD KEY `fk_ticket_activo` (`activo_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_profiles` (`profile_id`),
  ADD KEY `fk_users_maestro_area` (`area_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activos_ti`
--
ALTER TABLE `activos_ti`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_incidentes`
--
ALTER TABLE `historial_incidentes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maestro_areas`
--
ALTER TABLE `maestro_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maestro_confidencialidad`
--
ALTER TABLE `maestro_confidencialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `maestro_criticidad`
--
ALTER TABLE `maestro_criticidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `maestro_estado_activo`
--
ALTER TABLE `maestro_estado_activo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `maestro_estado_ticket`
--
ALTER TABLE `maestro_estado_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `maestro_severidad`
--
ALTER TABLE `maestro_severidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `maestro_tipos_activos`
--
ALTER TABLE `maestro_tipos_activos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `maestro_ubicacion_fisica`
--
ALTER TABLE `maestro_ubicacion_fisica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tickets_incidentes`
--
ALTER TABLE `tickets_incidentes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activos_ti`
--
ALTER TABLE `activos_ti`
  ADD CONSTRAINT `fk_activo_confidencialidad` FOREIGN KEY (`confidencialidad_id`) REFERENCES `maestro_confidencialidad` (`id`),
  ADD CONSTRAINT `fk_activo_criticidad` FOREIGN KEY (`criticidad_id`) REFERENCES `maestro_criticidad` (`id`),
  ADD CONSTRAINT `fk_activo_estado` FOREIGN KEY (`estado_id`) REFERENCES `maestro_estado_activo` (`id`),
  ADD CONSTRAINT `fk_activo_tipo` FOREIGN KEY (`tipo_id`) REFERENCES `maestro_tipos_activos` (`id`),
  ADD CONSTRAINT `fk_activo_ubicacion` FOREIGN KEY (`ubicacion_id`) REFERENCES `maestro_ubicacion_fisica` (`id`);

--
-- Filtros para la tabla `historial_incidentes`
--
ALTER TABLE `historial_incidentes`
  ADD CONSTRAINT `fk_historial_incidente` FOREIGN KEY (`incidente_id`) REFERENCES `tickets_incidentes` (`id`);

--
-- Filtros para la tabla `tickets_incidentes`
--
ALTER TABLE `tickets_incidentes`
  ADD CONSTRAINT `fk_ticket_activo` FOREIGN KEY (`activo_id`) REFERENCES `activos_ti` (`id`),
  ADD CONSTRAINT `fk_ticket_estado` FOREIGN KEY (`estado_id`) REFERENCES `maestro_estado_ticket` (`id`),
  ADD CONSTRAINT `fk_ticket_severidad` FOREIGN KEY (`severidad_id`) REFERENCES `maestro_severidad` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_maestro_area` FOREIGN KEY (`area_id`) REFERENCES `maestro_areas` (`id`),
  ADD CONSTRAINT `fk_users_profiles` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
