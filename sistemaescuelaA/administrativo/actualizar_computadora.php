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

/* Obtener y validar datos */
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$id_lab = filter_input(INPUT_POST, "id_laboratorio", FILTER_VALIDATE_INT);
$numero = filter_input(INPUT_POST, "numero_pc", FILTER_VALIDATE_INT);

$estado = trim($_POST["estado"] ?? "");

/* Validar IDs y número de PC */
if ($id === false || $id === null || $id <= 0) {
    exit("ID de computadora inválido.");
}

if ($id_lab === false || $id_lab === null || $id_lab <= 0) {
    exit("Laboratorio inválido.");
}

if ($numero === false || $numero === null || $numero <= 0) {
    exit("Número de PC inválido.");
}

/* Lista blanca de estados permitidos */
$estadosPermitidos = ["Alta", "Baja"];

if (!in_array($estado, $estadosPermitidos, true)) {
    exit("Estado inválido.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "UPDATE computadoras
     SET id_laboratorio = ?,
         numero_pc = ?,
         estado = ?
     WHERE id_computadora = ?"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

/* Vincular parámetros */
mysqli_stmt_bind_param(
    $stmt,
    "iisi",
    $id_lab,
    $numero,
    $estado,
    $id
);

/* Ejecutar actualización */
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    exit("Error al actualizar la computadora.");
}

/* Verificar si realmente existe */
if (mysqli_stmt_affected_rows($stmt) === 0) {
    mysqli_stmt_close($stmt);
    exit("No se encontró la computadora o no hubo cambios.");
}

mysqli_stmt_close($stmt);

/* Volver al listado */
header("Location: computadoras.php");
exit();

?>