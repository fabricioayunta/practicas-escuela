<?php

session_start();

/* Verificar sesión y rol */
if (
    !isset($_SESSION["pi_id_usuario"]) ||
    !isset($_SESSION["pi_id_rol"]) ||
    (int)$_SESSION["pi_id_rol"] !== 3
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");
require_once("../includes/funciones_pi.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de préstamo inválido.", "prestamos.php");
}

/* Marcar el insumo como devuelto */
if (!devolver_prestamo_insumo($conexion, $id)) {
    mostrar_error("No se pudo registrar la devolución.", "prestamos.php");
}

header("Location: prestamos.php?ok=devuelto");
exit();
?>
