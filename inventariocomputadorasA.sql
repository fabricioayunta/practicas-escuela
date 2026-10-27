-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2026 a las 21:58:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `inventariocomputadorasA` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `inventariocomputadorasA`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventariocomputadorasA`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes`
--

CREATE TABLE `componentes` (
  `id_componente` int(11) NOT NULL,
  `id_computadora` int(11) NOT NULL,
  `mother` varchar(100) DEFAULT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `memoria_ram` varchar(100) DEFAULT NULL,
  `disco` varchar(100) DEFAULT NULL,
  `monitor` varchar(100) DEFAULT NULL,
  `teclado` varchar(100) DEFAULT NULL,
  `mouse` varchar(100) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`id_componente`, `id_computadora`, `mother`, `procesador`, `memoria_ram`, `disco`, `monitor`, `teclado`, `mouse`, `observaciones`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 76, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 78, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 81, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 83, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 84, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(85, 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 86, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 87, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 88, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 90, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `computadoras`
--

CREATE TABLE `computadoras` (
  `id_computadora` int(11) NOT NULL,
  `numero_pc` int(11) NOT NULL,
  `id_laboratorio` int(11) NOT NULL,
  `estado` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `computadoras`
--

INSERT INTO `computadoras` (`id_computadora`, `numero_pc`, `id_laboratorio`, `estado`) VALUES
(1, 1, 1, 'Alta'),
(2, 2, 1, 'Alta'),
(3, 3, 1, 'Alta'),
(4, 4, 1, 'Alta'),
(5, 5, 1, 'Alta'),
(6, 6, 1, 'Alta'),
(7, 7, 1, 'Alta'),
(8, 8, 1, 'Alta'),
(9, 9, 1, 'Alta'),
(10, 10, 1, 'Alta'),
(11, 11, 1, 'Alta'),
(12, 12, 1, 'Alta'),
(13, 13, 1, 'Alta'),
(14, 14, 1, 'Alta'),
(15, 15, 1, 'Alta'),
(16, 16, 1, 'Alta'),
(17, 17, 1, 'Alta'),
(18, 18, 1, 'Alta'),
(19, 1, 2, 'Alta'),
(20, 2, 2, 'Alta'),
(21, 3, 2, 'Alta'),
(22, 4, 2, 'Alta'),
(23, 5, 2, 'Alta'),
(24, 6, 2, 'Alta'),
(25, 7, 2, 'Alta'),
(26, 8, 2, 'Alta'),
(27, 9, 2, 'Alta'),
(28, 10, 2, 'Alta'),
(29, 11, 2, 'Alta'),
(30, 12, 2, 'Alta'),
(31, 13, 2, 'Alta'),
(32, 14, 2, 'Alta'),
(33, 15, 2, 'Alta'),
(34, 16, 2, 'Alta'),
(35, 17, 2, 'Alta'),
(36, 18, 2, 'Alta'),
(37, 1, 3, 'Alta'),
(38, 2, 3, 'Alta'),
(39, 3, 3, 'Alta'),
(40, 4, 3, 'Alta'),
(41, 5, 3, 'Alta'),
(42, 6, 3, 'Alta'),
(43, 7, 3, 'Alta'),
(44, 8, 3, 'Alta'),
(45, 9, 3, 'Alta'),
(46, 10, 3, 'Alta'),
(47, 11, 3, 'Alta'),
(48, 12, 3, 'Alta'),
(49, 13, 3, 'Alta'),
(50, 14, 3, 'Alta'),
(51, 15, 3, 'Alta'),
(52, 16, 3, 'Alta'),
(53, 17, 3, 'Alta'),
(54, 18, 3, 'Alta'),
(55, 1, 4, 'Alta'),
(56, 2, 4, 'Alta'),
(57, 3, 4, 'Alta'),
(58, 4, 4, 'Alta'),
(59, 5, 4, 'Alta'),
(60, 6, 4, 'Alta'),
(61, 7, 4, 'Alta'),
(62, 8, 4, 'Alta'),
(63, 9, 4, 'Alta'),
(64, 10, 4, 'Alta'),
(65, 11, 4, 'Alta'),
(66, 12, 4, 'Alta'),
(67, 13, 4, 'Alta'),
(68, 14, 4, 'Alta'),
(69, 15, 4, 'Alta'),
(70, 16, 4, 'Alta'),
(71, 17, 4, 'Alta'),
(72, 18, 4, 'Alta'),
(73, 1, 5, 'Alta'),
(74, 2, 5, 'Alta'),
(75, 3, 5, 'Alta'),
(76, 4, 5, 'Alta'),
(77, 5, 5, 'Alta'),
(78, 6, 5, 'Alta'),
(79, 7, 5, 'Alta'),
(80, 8, 5, 'Alta'),
(81, 9, 5, 'Alta'),
(82, 10, 5, 'Alta'),
(83, 11, 5, 'Alta'),
(84, 12, 5, 'Alta'),
(85, 13, 5, 'Alta'),
(86, 14, 5, 'Alta'),
(87, 15, 5, 'Alta'),
(88, 16, 5, 'Alta'),
(89, 17, 5, 'Alta'),
(90, 18, 5, 'Alta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialticket`
--

CREATE TABLE `historialticket` (
  `id_historial` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `estado` enum('Abierto','Pendiente','Cerrado') NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `observacion` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_computadoras`
--

CREATE TABLE `historial_computadoras` (
  `id_historial` int(11) NOT NULL,
  `id_computadora` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorios`
--

CREATE TABLE `laboratorios` (
  `id_laboratorio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `laboratorios`
--

INSERT INTO `laboratorios` (`id_laboratorio`, `nombre`) VALUES
(1, 'Laboratorio 1'),
(2, 'Laboratorio 2'),
(3, 'Laboratorio 3'),
(4, 'Laboratorio Circular'),
(5, 'Multimedia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relevamientos`
-- (relevamiento y habilitación de laboratorios por turno)
--

CREATE TABLE `relevamientos` (
  `id_relevamiento` int(11) NOT NULL,
  `id_laboratorio` int(11) DEFAULT NULL,
  `laboratorio_nombre` varchar(50) NOT NULL,
  `turno` varchar(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `resultado` enum('Todo OK','Con inconvenientes') NOT NULL,
  `total_computadoras` int(11) NOT NULL DEFAULT 0,
  `revisadas` int(11) NOT NULL DEFAULT 0,
  `con_inconvenientes` int(11) NOT NULL DEFAULT 0,
  `observaciones` varchar(255) DEFAULT NULL,
  `token_envio` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relevamiento_detalle`
--

CREATE TABLE `relevamiento_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_relevamiento` int(11) NOT NULL,
  `id_computadora` int(11) DEFAULT NULL,
  `numero_pc` int(11) NOT NULL,
  `estado` enum('En condiciones','Con inconvenientes','Fuera de servicio') NOT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `id_ticket` int(11) DEFAULT NULL,
  `ticket_nuevo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'Administrativo'),
(3, 'EMATP'),
(2, 'Profesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id_ticket` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `componentes_afectados` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` enum('Abierto','Pendiente','Cerrado') NOT NULL DEFAULT 'Abierto',
  `id_usuario` int(11) NOT NULL,
  `id_computadora` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `id_ematp_asignado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `contrasena`, `id_rol`) VALUES
(1, 'Marta', 'Soria', 'profe@gmail.com', '$2y$10$CHN2xhNvGIMw/dR.ubUT4eW8EXNU3jWwOwyGk4itFaBn8uPi8t3jO', 2),
(2, 'Juan', 'Perez', 'ematp@gmail.com', '$2y$10$.DJSXNxRDzFd0VKMY2L3fO8Ky4iKqDdm4yw66IOL/eixmXN3nurdi', 3),
(4, 'Administrador', 'Sistema', 'admin@gmail.com', '$2y$10$OzOMi638lj8M41fJx50X..NYDe/720YqoRcjMXs4IxVNoBjOLERPC', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD PRIMARY KEY (`id_componente`),
  ADD KEY `id_computadora` (`id_computadora`);

--
-- Indices de la tabla `computadoras`
--
ALTER TABLE `computadoras`
  ADD PRIMARY KEY (`id_computadora`),
  ADD UNIQUE KEY `uq_pc_laboratorio` (`numero_pc`,`id_laboratorio`),
  ADD KEY `id_laboratorio` (`id_laboratorio`);

--
-- Indices de la tabla `historialticket`
--
ALTER TABLE `historialticket`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_ticket` (`id_ticket`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `historial_computadoras`
--
ALTER TABLE `historial_computadoras`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_computadora` (`id_computadora`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_laboratorio`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `relevamientos`
--
ALTER TABLE `relevamientos`
  ADD PRIMARY KEY (`id_relevamiento`),
  ADD UNIQUE KEY `uq_relevamiento_token` (`token_envio`),
  ADD KEY `idx_rel_laboratorio_fecha` (`id_laboratorio`,`fecha`),
  ADD KEY `idx_rel_usuario` (`id_usuario`),
  ADD KEY `idx_rel_fecha` (`fecha`);

--
-- Indices de la tabla `relevamiento_detalle`
--
ALTER TABLE `relevamiento_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD UNIQUE KEY `uq_detalle_relevamiento_pc` (`id_relevamiento`,`numero_pc`),
  ADD KEY `idx_det_computadora` (`id_computadora`,`id_relevamiento`),
  ADD KEY `idx_det_ticket` (`id_ticket`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_computadora` (`id_computadora`),
  ADD KEY `id_ematp_asignado` (`id_ematp_asignado`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `computadoras`
--
ALTER TABLE `computadoras`
  MODIFY `id_computadora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `historialticket`
--
ALTER TABLE `historialticket`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_computadoras`
--
ALTER TABLE `historial_computadoras`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `relevamientos`
--
ALTER TABLE `relevamientos`
  MODIFY `id_relevamiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `relevamiento_detalle`
--
ALTER TABLE `relevamiento_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD CONSTRAINT `fk_componente_computadora` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `computadoras`
--
ALTER TABLE `computadoras`
  ADD CONSTRAINT `fk_computadora_laboratorio` FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios` (`id_laboratorio`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historialticket`
--
ALTER TABLE `historialticket`
  ADD CONSTRAINT `fk_historial_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_computadoras`
--
ALTER TABLE `historial_computadoras`
  ADD CONSTRAINT `fk_historial_pc` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_pc_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `relevamientos`
--
ALTER TABLE `relevamientos`
  ADD CONSTRAINT `fk_rel_laboratorio` FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rel_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `relevamiento_detalle`
--
ALTER TABLE `relevamiento_detalle`
  ADD CONSTRAINT `fk_det_relevamiento` FOREIGN KEY (`id_relevamiento`) REFERENCES `relevamientos` (`id_relevamiento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_det_computadora` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_det_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_ticket_computadora` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticket_ematp` FOREIGN KEY (`id_ematp_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticket_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;

-- ========================================================
-- MÓDULOS: Mesa de Ayuda de Laboratorios, de Máquinas de Impresión 3D y de Herramientas
-- ========================================================

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `carpeta` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `nombre`, `carpeta`) VALUES
(1, 'Mesa de Ayuda de Laboratorios', 'laboratorios'),
(2, 'Mesa de Ayuda de Máquinas de Impresión 3D', 'impresion3d'),
(3, 'Mesa de Ayuda de Herramientas', 'pañol');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_modulos`
--

CREATE TABLE `usuarios_modulos` (
  `id_usuario_modulo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_modulos`
--

-- IDs según los usuarios de esta base: 4 = admin, 2 = ematp, 1 = profe
INSERT INTO `usuarios_modulos` (`id_usuario_modulo`, `id_usuario`, `id_modulo`) VALUES
(1, 4, 1),
(2, 4, 2),
(3, 4, 3),
(4, 2, 1),
(5, 2, 2),
(6, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repuestos`
--

CREATE TABLE `repuestos` (
  `id_repuesto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `repuestos`
--

INSERT INTO `repuestos` (`id_repuesto`, `nombre`, `descripcion`, `cantidad`) VALUES
(1, 'Memoria RAM DDR4 8 GB', 'Módulos sueltos para recambio', 6),
(2, 'Fuente 500W', 'Fuentes ATX de repuesto', 3),
(3, 'Cable HDMI 1,5 m', 'Cables para monitores', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_insumos`
--

CREATE TABLE `prestamos_insumos` (
  `id_prestamo` int(11) NOT NULL,
  `insumo` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `persona` varchar(100) NOT NULL,
  `fecha_entrega` datetime DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` enum('Pendiente','Devuelto') NOT NULL DEFAULT 'Pendiente',
  `observaciones` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id_articulo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `estado` enum('Alta','Baja') NOT NULL DEFAULT 'Alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id_articulo`, `nombre`, `descripcion`, `cantidad`, `estado`) VALUES
(1, 'Taladro percutor', 'Taller de electromecánica', 2, 'Alta'),
(2, 'Juego de destornilladores', 'Set de 12 piezas', 5, 'Alta'),
(3, 'Multímetro digital', 'Laboratorio de electrónica', 4, 'Alta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_articulos`
--

CREATE TABLE `prestamos_articulos` (
  `id_prestamo` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `persona` varchar(100) NOT NULL,
  `fecha_prestamo` datetime DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` enum('Pendiente','Devuelto') NOT NULL DEFAULT 'Pendiente',
  `observaciones` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  ADD PRIMARY KEY (`id_usuario_modulo`),
  ADD UNIQUE KEY `uq_usuario_modulo` (`id_usuario`,`id_modulo`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `repuestos`
--
ALTER TABLE `repuestos`
  ADD PRIMARY KEY (`id_repuesto`);

--
-- Indices de la tabla `prestamos_insumos`
--
ALTER TABLE `prestamos_insumos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id_articulo`);

--
-- Indices de la tabla `prestamos_articulos`
--
ALTER TABLE `prestamos_articulos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_articulo` (`id_articulo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  MODIFY `id_usuario_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `repuestos`
--
ALTER TABLE `repuestos`
  MODIFY `id_repuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamos_insumos`
--
ALTER TABLE `prestamos_insumos`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamos_articulos`
--
ALTER TABLE `prestamos_articulos`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Filtros para la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  ADD CONSTRAINT `usuarios_modulos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `usuarios_modulos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`);

--
-- Filtros para la tabla `prestamos_insumos`
--
ALTER TABLE `prestamos_insumos`
  ADD CONSTRAINT `prestamos_insumos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `prestamos_articulos`
--
ALTER TABLE `prestamos_articulos`
  ADD CONSTRAINT `prestamos_articulos_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`),
  ADD CONSTRAINT `prestamos_articulos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
