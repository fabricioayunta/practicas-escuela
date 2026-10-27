<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos_vistas.php";

$id = rel_entero($_GET["id"] ?? "");

$titulo = "Historial de relevamientos";
$menu   = "ematp";

include "../includes/cabecera.php";

rel_vista_historial_pc($conexion, $id, 3);

include "../includes/pie.php";
