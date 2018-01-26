-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-12-2016 a las 13:00:34
-- Versión del servidor: 5.7.13-0ubuntu0.16.04.2
-- Versión de PHP: 7.0.8-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `grupo15`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CATEGORIA`
--

CREATE TABLE `CATEGORIA` (
  `id` int(255) NOT NULL,
  `categoria_padre_id` int(255) DEFAULT NULL,
  `nombre` varchar(45) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `CATEGORIA`
--

INSERT INTO `CATEGORIA` (`id`, `categoria_padre_id`, `nombre`, `bajaLogica`) VALUES
(2, NULL, 'BEBIDAS', 0),
(3, 2, 'CERVEZAS', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `COMPRA`
--

CREATE TABLE `COMPRA` (
  `id` int(255) NOT NULL,
  `id_proveedor` int(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `factura` longblob NOT NULL,
  `finalizada` int(1) NOT NULL DEFAULT '0',
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CONFIGURACION`
--

CREATE TABLE `CONFIGURACION` (
  `id` int(255) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `sitioHabilitadoMsj` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sitioHabilitado` tinyint(1) NOT NULL DEFAULT '1',
  `paginado_numero` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `CONFIGURACION`
--

INSERT INTO `CONFIGURACION` (`id`, `titulo`, `sitioHabilitadoMsj`, `descripcion`, `email`, `sitioHabilitado`, `paginado_numero`) VALUES
(3, 'Buffet de la Facultad', 'El sitio no se encuentra habilitado. Disculpe las molestias.', 'El Buffet - UNLP 2016 es un sistema que permite llevar a cabo la administración de los productos y servicios relacionados con la gestión del Buffet de la Facultad de Informática. Además, te permitirá realizar pedidos online.', 'grupo15@gmail.com', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DETALLE_EGRESO`
--

CREATE TABLE `DETALLE_EGRESO` (
  `id` int(255) NOT NULL,
  `id_compra` int(255) DEFAULT NULL,
  `id_producto` int(255) DEFAULT NULL,
  `id_tipo_egreso` int(255) NOT NULL,
  `cantidad` int(255) NOT NULL,
  `precio_unitario` float NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DETALLE_INGRESO`
--

CREATE TABLE `DETALLE_INGRESO` (
  `id` int(255) NOT NULL,
  `id_venta` int(255) DEFAULT NULL,
  `id_tipo_ingreso` int(255) NOT NULL,
  `id_producto` int(255) DEFAULT NULL,
  `cantidad` int(255) NOT NULL,
  `precio_unitario` float NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `DETALLE_INGRESO`
--

INSERT INTO `DETALLE_INGRESO` (`id`, `id_venta`, `id_tipo_ingreso`, `id_producto`, `cantidad`, `precio_unitario`, `descripcion`, `fecha`, `bajaLogica`) VALUES
(12, 9, 4, 2, 1, 12, '', '2016-11-28 18:52:15', 0),
(13, 10, 4, 2, 1, 12, '', '2016-11-28 18:55:29', 0),
(14, 11, 4, 2, 1, 12, '', '2016-11-28 18:55:43', 0),
(15, 12, 4, 2, 1, 12, '', '2016-11-28 18:58:19', 0),
(16, 13, 4, 2, 1, 12, '', '2016-11-28 19:12:31', 0),
(17, 14, 4, 2, 1, 12, '', '2016-11-28 19:12:42', 0),
(18, 15, 4, 2, 1, 12, '', '2016-11-28 19:12:57', 0),
(19, 16, 4, 2, 1, 12, '', '2016-11-28 19:13:23', 0),
(20, 17, 4, 2, 1, 12, '', '2016-11-28 19:15:46', 0),
(21, 18, 4, 2, 1, 12, '', '2016-11-28 19:16:22', 0),
(22, 19, 4, 2, 1, 12, '', '2016-11-28 19:17:29', 0),
(23, 20, 4, 2, 1, 12, '', '2016-11-28 19:22:13', 0),
(24, 21, 4, 2, 1, 12, '', '2016-11-28 19:23:36', 0),
(25, 22, 4, 2, 1, 12, '', '2016-11-28 19:42:48', 0),
(26, 22, 4, 2, 1, 12, '', '2016-11-28 19:42:48', 0),
(27, 22, 4, 2, 1, 12, '', '2016-11-28 19:42:48', 0),
(28, 23, 4, 2, 1, 12, '', '2016-11-28 19:44:05', 0),
(29, 23, 4, 2, 1, 12, '', '2016-11-28 19:44:05', 0),
(30, 24, 4, 2, 1, 12, '', '2016-11-28 19:48:42', 0),
(31, 24, 4, 2, 1, 12, '', '2016-11-28 19:48:42', 0),
(32, 25, 4, 2, 1, 12, '', '2016-11-28 19:49:24', 0),
(33, 25, 4, 2, 1, 12, '', '2016-11-28 19:49:24', 0),
(34, 25, 4, 2, 1, 12, '', '2016-11-28 19:49:24', 0),
(35, 26, 4, 2, 1, 12, '', '2016-11-28 19:50:36', 0),
(36, 26, 4, 2, 1, 12, '', '2016-11-28 19:50:36', 0),
(37, 27, 4, 2, 1, 12, '', '2016-11-28 19:51:15', 0),
(38, 27, 4, 2, 1, 12, '', '2016-11-28 19:51:15', 0),
(39, 27, 4, 2, 1, 12, '', '2016-11-28 19:51:15', 0),
(40, 28, 4, 2, 1, 12, '', '2016-11-28 19:56:56', 0),
(41, 28, 4, 2, 1, 12, '', '2016-11-28 19:56:56', 0),
(42, 29, 4, 2, 1, 12, 'svgsdfgsd', '2016-11-28 20:11:14', 0),
(43, 29, 4, 2, 1, 12, '1 scvsdfvs ', '2016-11-28 20:11:14', 0),
(44, 29, 4, 2, 1, 12, '', '2016-11-28 20:11:14', 0),
(45, 29, 4, 2, 3, 12, '', '2016-11-28 20:11:14', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DETALLE_PEDIDO`
--

CREATE TABLE `DETALLE_PEDIDO` (
  `id` int(255) NOT NULL,
  `id_pedido` int(255) NOT NULL,
  `id_producto` int(255) NOT NULL,
  `cantidad` int(255) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `MENU_DIA`
--

CREATE TABLE `MENU_DIA` (
  `id` int(255) NOT NULL,
  `id_producto` int(255) NOT NULL,
  `fecha` date NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT '1',
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PEDIDO`
--

CREATE TABLE `PEDIDO` (
  `id` int(255) NOT NULL,
  `id_estado` int(255) NOT NULL,
  `id_usuario` int(255) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `observacion` varchar(255) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PEDIDO_ESTADO`
--

CREATE TABLE `PEDIDO_ESTADO` (
  `id` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `PEDIDO_ESTADO`
--

INSERT INTO `PEDIDO_ESTADO` (`id`, `nombre`, `bajaLogica`) VALUES
(1, 'ACEPTADO', 0),
(2, 'CANCELADO', 0),
(3, 'PENDIENTE', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PRODUCTO`
--

CREATE TABLE `PRODUCTO` (
  `id` int(255) NOT NULL,
  `id_categoria` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `marca` varchar(45) NOT NULL,
  `codigo_barra` varchar(45) NOT NULL,
  `stock` int(255) NOT NULL DEFAULT '0',
  `stock_minimo` int(255) NOT NULL,
  `precio_venta_unitario` float NOT NULL,
  `descripcion` varchar(225) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `PRODUCTO`
--

INSERT INTO `PRODUCTO` (`id`, `id_categoria`, `nombre`, `marca`, `codigo_barra`, `stock`, `stock_minimo`, `precio_venta_unitario`, `descripcion`, `fecha_alta`, `bajaLogica`) VALUES
(2, 3, 'Quilmes', 'Quilmes', '1234', 100, 24, 12, 'laldsflasdfas', '2016-10-27 00:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PROVEEDOR`
--

CREATE TABLE `PROVEEDOR` (
  `id` int(255) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `cuit` varchar(15) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ROL`
--

CREATE TABLE `ROL` (
  `id` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ROL`
--

INSERT INTO `ROL` (`id`, `nombre`, `bajaLogica`) VALUES
(10, 'ADMINISTRADOR', 0),
(11, 'GESTION', 0),
(12, 'USUARIO ONLINE', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPO_EGRESO`
--

CREATE TABLE `TIPO_EGRESO` (
  `id` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TIPO_INGRESO`
--

CREATE TABLE `TIPO_INGRESO` (
  `id` int(10) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `TIPO_INGRESO`
--

INSERT INTO `TIPO_INGRESO` (`id`, `nombre`, `bajaLogica`) VALUES
(4, 'VENTA MOSTRADOR', 0),
(5, 'VENTA ONLINE', 0),
(6, 'FACULTAD BECAS', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UBICACION`
--

CREATE TABLE `UBICACION` (
  `id` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `UBICACION`
--

INSERT INTO `UBICACION` (`id`, `nombre`, `descripcion`, `bajaLogica`) VALUES
(7, 'LIFIA', NULL, 0),
(8, 'LINTI', NULL, 0),
(9, 'LEGAJO', NULL, 0),
(10, 'LIDI', NULL, 0),
(11, 'FOTOCOPIADO', NULL, 0),
(12, 'DECANATO', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO`
--

CREATE TABLE `USUARIO` (
  `id` int(255) NOT NULL,
  `id_rol` int(255) NOT NULL,
  `id_ubicacion` int(255) DEFAULT '0',
  `usuario` varchar(45) NOT NULL,
  `clave` longtext NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `documento` int(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `token` longtext,
  `identificador` longtext NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT '1',
  `bajaLogica` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `USUARIO`
--

INSERT INTO `USUARIO` (`id`, `id_rol`, `id_ubicacion`, `usuario`, `clave`, `nombre`, `apellido`, `documento`, `email`, `telefono`, `token`, `identificador`, `habilitado`, `bajaLogica`) VALUES
(6, 10, NULL, 'admin', '502f6f643a4181800f70e4fdba42052b1730a82d7b46959fbaeadfe4383074005c7f5d6d1802e65b82870a8e24bc68c1af1e4826d940151739e9c7dff1a25a3c', 'Administrador', 'Administrador', 30454258, 'admin@admin.com', '4556598', 'NO_TOKEN', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 1, 0),
(7, 11, NULL, 'gestion', '545ad47d08cb9b7b49d401199f2be656ba5d381cfa6676ba9b854e6070db5fe75363122866b00e138db5d1a5b465a3826536859ce4ad270d533df7a57f450d2e', 'Gestion', 'Gestion', 32656987, 'gestion@gestion.com', 'telefono', NULL, '5d5052f6e839217f24943a9ccf97c946b0915682b9aec2a694a9c38ffc44346c596cd2b79732f387aa3cf61388e8841018386050693af9dc859809ba61b83fd1', 1, 0),
(8, 12, 11, 'usuario', 'e30ec606bc39730b8221c7bfe0215f2445e9163db49fd6b7b801a2ca02b1a22ce206edd7cde7242bf4d8880257d3773d160eab80725d262d4d523abcad8166ba', 'Usuario Online', 'Usuario Online', 33254852, 'usuario@usuario.com', 'telefono', NULL, 'd9e94fd2b4c5522e5bb7996aa4df48a3f6b8f1b0c3e7fadb5fcc724e3ab6d85dc401b0a2789fe56c209b80e86102b218ff74ff8614f315599a180691846138b6', 1, 0),
(12, 12, 7, 'aasdfas', '79b2e3d0dc5bfed2b3076899744fc0cd5139cafff693cc3d7a50404a8276b12abe221de85cd3899d6f35029466fda520b903181e57b2cb486ee0e509f252da5c', 'asdf', 'asdf', 32423, 'asdfa@asdfa.com', 'telefono', NULL, '68b8450eed8f6b53f8c4816192fc2e6ab40c81d1b7f827c8dba7af18aa2f9f6c7f3a1e591132c6a3ac64882480c51dbc7f69b6e85dae875d89683dd0bd75c309', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VENTA`
--

CREATE TABLE `VENTA` (
  `id` int(255) NOT NULL,
  `id_empleado` int(255) NOT NULL,
  `id_pedido` int(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bajaLogica` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `VENTA`
--

INSERT INTO `VENTA` (`id`, `id_empleado`, `id_pedido`, `fecha`, `bajaLogica`) VALUES
(9, 6, NULL, '2016-11-28 18:52:15', 0),
(10, 6, NULL, '2016-11-28 18:55:29', 0),
(11, 6, NULL, '2016-11-28 18:55:43', 0),
(12, 6, NULL, '2016-11-28 18:58:19', 0),
(13, 6, NULL, '2016-11-28 19:12:31', 0),
(14, 6, NULL, '2016-11-28 19:12:42', 0),
(15, 6, NULL, '2016-11-28 19:12:57', 0),
(16, 6, NULL, '2016-11-28 19:13:23', 0),
(17, 6, NULL, '2016-11-28 19:15:46', 0),
(18, 6, NULL, '2016-11-28 19:16:22', 0),
(19, 6, NULL, '2016-11-28 19:17:29', 0),
(20, 6, NULL, '2016-11-28 19:22:13', 0),
(21, 6, NULL, '2016-11-28 19:23:36', 0),
(22, 6, NULL, '2016-11-28 19:42:48', 0),
(23, 6, NULL, '2016-11-28 19:44:05', 0),
(24, 6, NULL, '2016-11-28 19:48:42', 0),
(25, 6, NULL, '2016-11-28 19:49:24', 0),
(26, 6, NULL, '2016-11-28 19:50:36', 0),
(27, 6, NULL, '2016-11-28 19:51:15', 0),
(28, 6, NULL, '2016-11-28 19:56:56', 0),
(29, 6, NULL, '2016-11-28 20:11:14', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `CATEGORIA`
--
ALTER TABLE `CATEGORIA`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_padre_id` (`categoria_padre_id`);

--
-- Indices de la tabla `COMPRA`
--
ALTER TABLE `COMPRA`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_COMPRA_1_idx` (`id_proveedor`);

--
-- Indices de la tabla `CONFIGURACION`
--
ALTER TABLE `CONFIGURACION`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `DETALLE_EGRESO`
--
ALTER TABLE `DETALLE_EGRESO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo_egreso` (`id_tipo_egreso`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `DETALLE_INGRESO`
--
ALTER TABLE `DETALLE_INGRESO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo_ingreso` (`id_tipo_ingreso`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `fk_DETALLE_INGRESO_3_idx` (`id_venta`);

--
-- Indices de la tabla `DETALLE_PEDIDO`
--
ALTER TABLE `DETALLE_PEDIDO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `MENU_DIA`
--
ALTER TABLE `MENU_DIA`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `PEDIDO`
--
ALTER TABLE `PEDIDO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `PEDIDO_ESTADO`
--
ALTER TABLE `PEDIDO_ESTADO`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `PRODUCTO`
--
ALTER TABLE `PRODUCTO`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barra` (`codigo_barra`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `PROVEEDOR`
--
ALTER TABLE `PROVEEDOR`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ROL`
--
ALTER TABLE `ROL`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `TIPO_EGRESO`
--
ALTER TABLE `TIPO_EGRESO`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `TIPO_INGRESO`
--
ALTER TABLE `TIPO_INGRESO`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `UBICACION`
--
ALTER TABLE `UBICACION`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_ubicacion` (`id_ubicacion`);

--
-- Indices de la tabla `VENTA`
--
ALTER TABLE `VENTA`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_VENTA_1_idx` (`id_empleado`),
  ADD KEY `fk_VENTA_2_idx` (`id_pedido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `CATEGORIA`
--
ALTER TABLE `CATEGORIA`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `COMPRA`
--
ALTER TABLE `COMPRA`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `CONFIGURACION`
--
ALTER TABLE `CONFIGURACION`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `DETALLE_EGRESO`
--
ALTER TABLE `DETALLE_EGRESO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `DETALLE_INGRESO`
--
ALTER TABLE `DETALLE_INGRESO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT de la tabla `MENU_DIA`
--
ALTER TABLE `MENU_DIA`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `PEDIDO`
--
ALTER TABLE `PEDIDO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `PEDIDO_ESTADO`
--
ALTER TABLE `PEDIDO_ESTADO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `PRODUCTO`
--
ALTER TABLE `PRODUCTO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `PROVEEDOR`
--
ALTER TABLE `PROVEEDOR`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ROL`
--
ALTER TABLE `ROL`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `TIPO_EGRESO`
--
ALTER TABLE `TIPO_EGRESO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `TIPO_INGRESO`
--
ALTER TABLE `TIPO_INGRESO`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `UBICACION`
--
ALTER TABLE `UBICACION`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `VENTA`
--
ALTER TABLE `VENTA`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `CATEGORIA`
--
ALTER TABLE `CATEGORIA`
  ADD CONSTRAINT `CATEGORIA_ibfk_1` FOREIGN KEY (`categoria_padre_id`) REFERENCES `CATEGORIA` (`id`);

--
-- Filtros para la tabla `COMPRA`
--
ALTER TABLE `COMPRA`
  ADD CONSTRAINT `fk_COMPRA_1` FOREIGN KEY (`id_proveedor`) REFERENCES `PROVEEDOR` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `DETALLE_EGRESO`
--
ALTER TABLE `DETALLE_EGRESO`
  ADD CONSTRAINT `fk_DETALLE_EGRESO_1` FOREIGN KEY (`id_tipo_egreso`) REFERENCES `TIPO_EGRESO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DETALLE_EGRESO_2` FOREIGN KEY (`id_compra`) REFERENCES `COMPRA` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DETALLE_EGRESO_3` FOREIGN KEY (`id_producto`) REFERENCES `PRODUCTO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `DETALLE_INGRESO`
--
ALTER TABLE `DETALLE_INGRESO`
  ADD CONSTRAINT `fk_DETALLE_INGRESO_1` FOREIGN KEY (`id_producto`) REFERENCES `PRODUCTO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DETALLE_INGRESO_2` FOREIGN KEY (`id_tipo_ingreso`) REFERENCES `TIPO_INGRESO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DETALLE_INGRESO_3` FOREIGN KEY (`id_venta`) REFERENCES `VENTA` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `DETALLE_PEDIDO`
--
ALTER TABLE `DETALLE_PEDIDO`
  ADD CONSTRAINT `fk_DETALLE_PEDIDO_1` FOREIGN KEY (`id_producto`) REFERENCES `PRODUCTO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DETALLE_PEDIDO_2` FOREIGN KEY (`id_pedido`) REFERENCES `PEDIDO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `MENU_DIA`
--
ALTER TABLE `MENU_DIA`
  ADD CONSTRAINT `fk_MENU_DIA_1` FOREIGN KEY (`id_producto`) REFERENCES `PRODUCTO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `PEDIDO`
--
ALTER TABLE `PEDIDO`
  ADD CONSTRAINT `fk_PEDIDO_1` FOREIGN KEY (`id_usuario`) REFERENCES `USUARIO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PEDIDO_2` FOREIGN KEY (`id_estado`) REFERENCES `PEDIDO_ESTADO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `PRODUCTO`
--
ALTER TABLE `PRODUCTO`
  ADD CONSTRAINT `fk_PRODUCTO_1` FOREIGN KEY (`id_categoria`) REFERENCES `CATEGORIA` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  ADD CONSTRAINT `fk_USUARIO_1` FOREIGN KEY (`id_ubicacion`) REFERENCES `UBICACION` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_USUARIO_2` FOREIGN KEY (`id_rol`) REFERENCES `ROL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `VENTA`
--
ALTER TABLE `VENTA`
  ADD CONSTRAINT `fk_VENTA_1` FOREIGN KEY (`id_empleado`) REFERENCES `USUARIO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_VENTA_2` FOREIGN KEY (`id_pedido`) REFERENCES `PEDIDO` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
