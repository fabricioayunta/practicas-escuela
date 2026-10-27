<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos_vistas.php";

$id = rel_entero($_GET["id"] ?? "");

$titulo = "Detalle del relevamiento";
$menu   = "admin";

include "../includes/cabecera.php";

rel_vista_detalle($conexion, $id, 1);

include "../includes/pie.php";
