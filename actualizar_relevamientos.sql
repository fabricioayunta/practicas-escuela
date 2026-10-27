-- ======================================================================
-- RELEVAMIENTO Y HABILITACIÓN DE LABORATORIOS POR TURNO
-- Base de datos: inventariocomputadorasA
-- ----------------------------------------------------------------------
-- Este script SOLO AGREGA: la columna tickets.componentes_afectados (si una
-- base vieja no la tiene) y dos tablas nuevas. No borra ni reemplaza
-- ninguna tabla ni registro existente.
--
-- Cómo usarlo: phpMyAdmin > base inventariocomputadorasA > pestaña SQL >
-- pegar todo este archivo > Continuar.
-- Se puede ejecutar más de una vez sin problemas (... IF NOT EXISTS).
--
-- Si usted importa desde cero el archivo inventariocomputadorasA.sql,
-- ESTE SCRIPT NO HACE FALTA: todo eso ya viene incluido.
-- ======================================================================

USE `inventariocomputadorasA`;

-- Componentes marcados al crear un ticket (Mother, Monitor, etc.).
-- Las bases instaladas antes de este cambio no la tienen.
ALTER TABLE `tickets`
  ADD COLUMN IF NOT EXISTS `componentes_afectados` varchar(255) DEFAULT NULL AFTER `descripcion`;

-- Un registro por cada relevamiento confirmado.
-- Nunca se modifica ni se borra desde el sistema (queda como historial).
CREATE TABLE IF NOT EXISTS `relevamientos` (
  `id_relevamiento`    int(11) NOT NULL AUTO_INCREMENT,
  `id_laboratorio`     int(11) DEFAULT NULL,
  `laboratorio_nombre` varchar(50) NOT NULL,
  `turno`              varchar(20) NOT NULL,
  `id_usuario`         int(11) NOT NULL,
  `fecha`              datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resultado`          enum('Todo OK','Con inconvenientes') NOT NULL,
  `total_computadoras` int(11) NOT NULL DEFAULT 0,
  `revisadas`          int(11) NOT NULL DEFAULT 0,
  `con_inconvenientes` int(11) NOT NULL DEFAULT 0,
  `observaciones`      varchar(255) DEFAULT NULL,
  `token_envio`        char(32) NOT NULL,
  PRIMARY KEY (`id_relevamiento`),
  UNIQUE KEY `uq_relevamiento_token` (`token_envio`),
  KEY `idx_rel_laboratorio_fecha` (`id_laboratorio`, `fecha`),
  KEY `idx_rel_usuario` (`id_usuario`),
  KEY `idx_rel_fecha` (`fecha`),
  CONSTRAINT `fk_rel_laboratorio` FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios` (`id_laboratorio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_rel_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Una fila por cada computadora del laboratorio en ese relevamiento.
--  * numero_pc se guarda también como número fijo: si más adelante se borra
--    la computadora, el historial sigue mostrando de qué equipo se trataba.
--  * id_ticket vincula el inconveniente con un ticket (nuevo o existente).
--  * ticket_nuevo = 1 si el ticket se creó desde este relevamiento.
CREATE TABLE IF NOT EXISTS `relevamiento_detalle` (
  `id_detalle`      int(11) NOT NULL AUTO_INCREMENT,
  `id_relevamiento` int(11) NOT NULL,
  `id_computadora`  int(11) DEFAULT NULL,
  `numero_pc`       int(11) NOT NULL,
  `estado`          enum('En condiciones','Con inconvenientes','Fuera de servicio') NOT NULL,
  `observacion`     varchar(500) DEFAULT NULL,
  `id_ticket`       int(11) DEFAULT NULL,
  `ticket_nuevo`    tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_detalle`),
  UNIQUE KEY `uq_detalle_relevamiento_pc` (`id_relevamiento`, `numero_pc`),
  KEY `idx_det_computadora` (`id_computadora`, `id_relevamiento`),
  KEY `idx_det_ticket` (`id_ticket`),
  CONSTRAINT `fk_det_relevamiento` FOREIGN KEY (`id_relevamiento`) REFERENCES `relevamientos` (`id_relevamiento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_det_computadora` FOREIGN KEY (`id_computadora`) REFERENCES `computadoras` (`id_computadora`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_det_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
