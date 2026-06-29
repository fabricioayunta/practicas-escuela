-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-06-2026 a las 21:01:07
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
-- Base de datos: `inventariocomputadoras`
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
(17, 1, 2, 'Alta'),
(18, 2, 2, 'Alta'),
(19, 3, 2, 'Alta'),
(20, 4, 2, 'Alta'),
(21, 5, 2, 'Alta'),
(22, 6, 2, 'Alta'),
(23, 7, 2, 'Alta'),
(24, 8, 2, 'Alta'),
(25, 9, 2, 'Alta'),
(26, 10, 2, 'Alta'),
(27, 11, 2, 'Alta'),
(28, 12, 2, 'Alta'),
(29, 13, 2, 'Alta'),
(30, 14, 2, 'Alta'),
(31, 15, 2, 'Alta'),
(32, 16, 2, 'Alta'),
(33, 1, 3, 'Alta'),
(34, 2, 3, 'Alta'),
(35, 3, 3, 'Alta'),
(36, 4, 3, 'Alta'),
(37, 5, 3, 'Alta'),
(38, 6, 3, 'Alta'),
(39, 7, 3, 'Alta'),
(40, 8, 3, 'Alta'),
(41, 9, 3, 'Alta'),
(42, 10, 3, 'Alta'),
(43, 11, 3, 'Alta'),
(44, 12, 3, 'Alta'),
(45, 13, 3, 'Alta'),
(46, 14, 3, 'Alta'),
(47, 15, 3, 'Alta'),
(48, 16, 3, 'Alta'),
(49, 1, 4, 'Alta'),
(50, 2, 4, 'Alta'),
(51, 3, 4, 'Alta'),
(52, 4, 4, 'Alta'),
(53, 5, 4, 'Alta'),
(54, 6, 4, 'Alta'),
(55, 7, 4, 'Alta'),
(56, 8, 4, 'Alta'),
(57, 9, 4, 'Alta'),
(58, 10, 4, 'Alta'),
(59, 11, 4, 'Alta'),
(60, 12, 4, 'Alta'),
(61, 13, 4, 'Alta'),
(62, 14, 4, 'Alta'),
(63, 15, 4, 'Alta'),
(64, 16, 4, 'Alta'),
(65, 1, 5, 'Alta'),
(66, 2, 5, 'Alta'),
(67, 3, 5, 'Alta'),
(68, 4, 5, 'Alta'),
(69, 5, 5, 'Alta'),
(70, 6, 5, 'Alta'),
(71, 7, 5, 'Alta'),
(72, 8, 5, 'Alta'),
(73, 9, 5, 'Alta'),
(74, 10, 5, 'Alta'),
(75, 11, 5, 'Alta'),
(76, 12, 5, 'Alta'),
(77, 13, 5, 'Alta'),
(78, 14, 5, 'Alta'),
(79, 15, 5, 'Alta'),
(80, 16, 5, 'Alta');

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

--
-- Volcado de datos para la tabla `historialticket`
--

INSERT INTO `historialticket` (`id_historial`, `id_ticket`, `estado`, `fecha`, `observacion`, `id_usuario`) VALUES
(1, 1, 'Pendiente', '2026-06-29 14:56:49', 'wow hemos observado que no funciona. pero no se comno sew arregla xd', 2),
(2, 1, 'Cerrado', '2026-06-29 14:57:19', 'listo\r\n', 2);

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
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` enum('Abierto','Pendiente','Cerrado') NOT NULL DEFAULT 'Abierto',
  `id_usuario` int(11) NOT NULL,
  `id_computadora` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `id_ematp_asignado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `titulo`, `descripcion`, `fecha_creacion`, `estado`, `id_usuario`, `id_computadora`, `foto`, `id_ematp_asignado`) VALUES
(1, 'usb delantero ', 'no anda los usb de adelante del gabinete', '2026-06-29 14:51:46', 'Cerrado', 3, 60, NULL, 2);

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
(1, 'admin', 'prueba', 'admin@gmail.com', '12345', 1),
(2, 'lucas', 'gomez', 'ematp@gmail.com', '12345', 3),
(3, 'marta', 'soria', 'profe@gmail.com', '12345', 2);

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
  ADD UNIQUE KEY `numero_pc` (`numero_pc`,`id_laboratorio`),
  ADD UNIQUE KEY `uq_pc_laboratorio` (`numero_pc`,`id_laboratorio`),
  ADD KEY `id_laboratorio` (`id_laboratorio`);

--
-- Indices de la tabla `historialticket`
--
ALTER TABLE `historialticket`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_ticket` (`id_ticket`),
  ADD KEY `fk_historial_usuario` (`id_usuario`);

--
-- Indices de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_laboratorio`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
  ADD KEY `fk_ticket_ematp` (`id_ematp_asignado`);

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
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `computadoras`
--
ALTER TABLE `computadoras`
  MODIFY `id_computadora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `historialticket`
--
ALTER TABLE `historialticket`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD CONSTRAINT `componentes_ibfk_1` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`);

--
-- Filtros para la tabla `computadoras`
--
ALTER TABLE `computadoras`
  ADD CONSTRAINT `computadoras_ibfk_1` FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios` (`id_laboratorio`);

--
-- Filtros para la tabla `historialticket`
--
ALTER TABLE `historialticket`
  ADD CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `historialticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_ticket_ematp` FOREIGN KEY (`id_ematp_asignado`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
