<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["pi_id_usuario"]) ||
    !isset($_SESSION["pi_id_rol"]) ||
    (int)$_SESSION["pi_id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de repuesto inválido.", "repuestos.php");
}

/* Preparar eliminación */
$stmt = mysqli_prepare($conexion, "DELETE FROM repuestos WHERE id_repuesto = ?");

if (!$stmt) {
    mostrar_error("Error al preparar la eliminación.", "repuestos.php");
}

mysqli_stmt_bind_param($stmt, "i", $id);

/* Ejecutar eliminación */
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mostrar_error("Error al eliminar el repuesto.", "repuestos.php");
}

mysqli_stmt_close($stmt);

/* Volver a repuestos */
header("Location: repuestos.php?ok=eliminado");
exit();
?>
