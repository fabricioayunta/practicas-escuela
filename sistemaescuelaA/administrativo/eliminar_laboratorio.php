<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["id_rol"]) ||
    (int)$_SESSION["id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    exit("ID de laboratorio inválido.");
}

/* Verificar si existen computadoras asociadas */
$stmt = mysqli_prepare(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM computadoras
     WHERE id_laboratorio = ?"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    exit("Error al verificar las computadoras.");
}

$resultado = mysqli_stmt_get_result($stmt);
$fila = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

/* No permitir eliminar si tiene computadoras */
$total = (int)($fila["total"] ?? 0);

if ($total > 0) {
    header("Location: laboratorios.php?error=tiene_computadoras");
    exit();
}

/* Preparar eliminación */
$stmt = mysqli_prepare(
    $conexion,
    "DELETE FROM laboratorios
     WHERE id_laboratorio = ?"
);

if (!$stmt) {
    exit("Error al preparar la eliminación.");
}

mysqli_stmt_bind_param($stmt, "i", $id);

/* Ejecutar eliminación */
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    exit("Error al eliminar el laboratorio.");
}

mysqli_stmt_close($stmt);

/* Volver a laboratorios */
header("Location: laboratorios.php");
exit();
?>