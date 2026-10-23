<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["panol_id_usuario"]) ||
    !isset($_SESSION["panol_id_rol"]) ||
    (int)$_SESSION["panol_id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de artículo inválido.", "articulos.php");
}

/* Verificar si tiene préstamos registrados */
$stmt = mysqli_prepare(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM prestamos_articulos
     WHERE id_articulo = ?"
);

if (!$stmt) {
    mostrar_error("Error al preparar la consulta.", "articulos.php");
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mostrar_error("Error al verificar los préstamos.", "articulos.php");
}

$fila = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* No permitir eliminar si tiene préstamos */
if ((int)($fila["total"] ?? 0) > 0) {
    header("Location: articulos.php?error=tiene_prestamos");
    exit();
}

/* Preparar eliminación */
$stmt = mysqli_prepare($conexion, "DELETE FROM articulos WHERE id_articulo = ?");

if (!$stmt) {
    mostrar_error("Error al preparar la eliminación.", "articulos.php");
}

mysqli_stmt_bind_param($stmt, "i", $id);

/* Ejecutar eliminación */
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mostrar_error("Error al eliminar el artículo.", "articulos.php");
}

mysqli_stmt_close($stmt);

/* Volver a artículos */
header("Location: articulos.php?ok=eliminado");
exit();
?>
