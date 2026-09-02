-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-08-2026 a las 21:24:39
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

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`id_componente`, `id_computadora`, `mother`, `procesador`, `memoria_ram`, `disco`, `monitor`, `teclado`, `mouse`, `observaciones`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'ASUS H610', 'Intel i5-12400', '16 GB', 'SSD  1T', ' Samsung 22', 'Logitech K120', 'Genius DX-120', ' Equipo actualizado.\r\n'),
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
(80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
(2, 1, 'Cerrado', '2026-06-29 14:57:19', 'listo\r\n', 2),
(3, 2, 'Pendiente', '2026-07-03 14:50:00', 'me ', 2),
(4, 3, 'Pendiente', '2026-08-07 15:50:06', 'visto', 2),
(5, 9, 'Pendiente', '2026-08-10 15:50:41', 'ererere', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_computadoras`
--

CREATE TABLE `historial_computadoras` (
  `id_historial` int(11) NOT NULL,
  `id_computadora` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_computadoras`
--

INSERT INTO `historial_computadoras` (`id_historial`, `id_computadora`, `id_usuario`, `accion`, `fecha`) VALUES
(1, 2, 2, 'Modificó los componentes de la computadora.', '2026-07-01 14:47:07'),
(2, 2, 2, 'Disco: SSD 480 GB → SSD  1T\n', '2026-07-01 14:52:32'),
(3, 2, 2, 'La computadora fue dada de baja.\nMotivo: sss', '2026-07-01 16:12:07'),
(4, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:12:10'),
(5, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:12:12'),
(6, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:12:13'),
(7, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:19:51'),
(8, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:24:54'),
(9, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:24:58'),
(10, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:34:29'),
(11, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:34:30'),
(12, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:34:30'),
(13, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:34:30'),
(14, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:30'),
(15, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:31'),
(16, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:31'),
(17, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:31'),
(18, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:32'),
(19, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:32'),
(20, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:33'),
(21, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:33'),
(22, 3, 2, 'La computadora volvió a estar operativa.', '2026-07-01 16:39:49'),
(23, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-03 13:25:17'),
(24, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-03 13:29:21'),
(25, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-03 13:54:58'),
(26, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-03 13:54:58'),
(27, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-03 13:54:59'),
(28, 2, 2, 'La computadora volvió a estar operativa.', '2026-07-03 13:57:41'),
(29, 2, 2, 'La computadora fue dada de alta', '2026-07-03 14:05:10'),
(30, 2, 2, 'La computadora fue dada de baja.\nMotivo: porque tengo ganas y soy el jefe\r\n', '2026-07-03 14:05:27'),
(31, 2, 2, 'La computadora fue dada de alta', '2026-07-03 14:07:13'),
(32, 2, 2, 'La computadora fue dada de baja.\nMotivo: prueba', '2026-07-06 14:16:41'),
(33, 2, 2, 'Memoria RAM: 32 GB → 16 GB\n', '2026-07-06 14:17:04'),
(34, 2, 2, 'La computadora fue dada de alta', '2026-07-06 14:17:05');

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
  `componentes_afectados` text DEFAULT NULL,
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

INSERT INTO `tickets` (`id_ticket`, `titulo`, `descripcion`, `componentes_afectados`, `fecha_creacion`, `estado`, `id_usuario`, `id_computadora`, `foto`, `id_ematp_asignado`) VALUES
(1, 'usb delantero ', 'no anda los usb de adelante del gabinete', NULL, '2026-06-29 14:51:46', 'Cerrado', 3, 60, NULL, 2),
(2, ' el telcado no anda', 'sf,jdsfg', NULL, '2026-07-03 14:49:12', 'Pendiente', 4, 37, NULL, 2),
(3, 'prueba imagen', 'prueba', NULL, '2026-08-05 16:22:19', 'Pendiente', 3, 1, '6a738d6bbbc7e.jpeg', 2),
(4, 'prueba213', 'hola', NULL, '2026-08-07 14:35:13', 'Abierto', 3, 33, NULL, NULL),
(5, 'Problema en: Mother, Memoria RAM, Teclado', 'NO ANDA  NADA', 'Mother, Memoria RAM, Teclado', '2026-08-10 14:54:09', 'Abierto', 3, 4, NULL, NULL),
(6, 'Problema en: Procesador, Disco, Mouse', 'No funciona', 'Procesador, Disco, Mouse', '2026-08-10 15:04:41', 'Abierto', 3, 29, NULL, NULL),
(7, 'Problema en: Mother, Memoria RAM, Teclado', 'prueba de imagen con el mejor auto', 'Mother, Memoria RAM, Teclado', '2026-08-10 15:09:20', 'Abierto', 3, 2, NULL, NULL),
(8, 'Problema en: Disco, Teclado', 'igkghkghk', 'Disco, Teclado', '2026-08-10 15:25:47', 'Abierto', 3, 31, 'ticket_6a7a17abcadb81.89306716.jpg', NULL),
(9, 'Problema en: Procesador, Monitor', 'gdfgdgdfgdfg', 'Procesador, Monitor', '2026-08-10 15:26:07', 'Pendiente', 3, 47, 'ticket_6a7a17bf82c809.36632045.jpg', 2),
(10, 'Problema en: Memoria RAM, Disco, Monitor', 'fytytyyyyty', 'Memoria RAM, Disco, Monitor', '2026-08-10 15:51:25', 'Abierto', 3, 30, 'ticket_6a7a1dad69d824.69732661.jpg', NULL);

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
(1, 'admin', 'pruebaa', 'admin@gmail.com', '12345', 1),
(2, 'lucas', 'gomez', 'ematp@gmail.com', '12345', 3),
(3, 'marta', 'soria', 'profe@gmail.com', '12345', 2),
(4, 'luciana', 'rodriguez', 'luly@gmail.com', '12345', 2);

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
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de la tabla `computadoras`
--
ALTER TABLE `computadoras`
  MODIFY `id_computadora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `historialticket`
--
ALTER TABLE `historialticket`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_computadoras`
--
ALTER TABLE `historial_computadoras`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Filtros para la tabla `historial_computadoras`
--
ALTER TABLE `historial_computadoras`
  ADD CONSTRAINT `historial_computadoras_ibfk_1` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`),
  ADD CONSTRAINT `historial_computadoras_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

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
(1, 'Laboratorios', 'sistemalaboratorios'),
(2, 'Pañol Informático', 'sistemapanolinformatico'),
(3, 'Pañol', 'sistemapanol');

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

INSERT INTO `usuarios_modulos` (`id_usuario_modulo`, `id_usuario`, `id_modulo`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 2, 2),
(6, 3, 1),
(7, 4, 1);

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
  MODIFY `id_usuario_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
