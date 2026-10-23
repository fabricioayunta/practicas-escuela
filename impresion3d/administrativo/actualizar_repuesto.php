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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: repuestos.php");
    exit();
}

if (!token_valido("pi_csrf_token")) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir la edición del repuesto.");
}

/* Obtener y limpiar datos */
$id          = (int)($_POST["id_repuesto"] ?? 0);
$nombre      = trim((string)($_POST["nombre"] ?? ""));
$descripcion = trim((string)($_POST["descripcion"] ?? ""));
$cantidad    = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);

/* Validaciones */
if ($id <= 0) {
    mostrar_error("Repuesto inválido.", "repuestos.php");
}

if ($nombre === "") {
    mostrar_error("El nombre del repuesto es obligatorio.");
}

if (mb_strlen($nombre) > 100 || mb_strlen($descripcion) > 255) {
    mostrar_error("El nombre o la descripción son demasiado largos.");
}

if ($cantidad === false || $cantidad < 0 || $cantidad > 9999) {
    mostrar_error("La cantidad debe ser un número entre 0 y 9999.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "UPDATE repuestos
     SET nombre = ?, descripcion = ?, cantidad = ?
     WHERE id_repuesto = ?"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("Error al preparar la consulta.");
}

mysqli_stmt_bind_param($stmt, "ssii", $nombre, $descripcion, $cantidad, $id);

/* Ejecutar */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: repuestos.php?ok=editado");
    exit();
}

/* Error */
error_log(mysqli_stmt_error($stmt));
mysqli_stmt_close($stmt);

mostrar_error("No se pudo actualizar el repuesto.");
?>
