<?php

session_start();

/* Verificar sesión y rol */
if (
    !isset($_SESSION["panol_id_usuario"]) ||
    !isset($_SESSION["panol_id_rol"]) ||
    (int)$_SESSION["panol_id_rol"] !== 3
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");
require_once("../includes/funciones_panol.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de préstamo inválido.", "prestamos.php");
}

/* Marcar el artículo como devuelto */
if (!devolver_prestamo_articulo($conexion, $id)) {
    mostrar_error("No se pudo registrar la devolución.", "prestamos.php");
}

header("Location: prestamos.php?ok=devuelto");
exit();
?>
